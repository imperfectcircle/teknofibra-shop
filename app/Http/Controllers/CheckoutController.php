<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Mail\NewOrderEmail;
use App\Enums\PaymentStatus;
use App\Models\DiscountCode;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\CartHelper as Cart;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    protected $euCountries;

    public function __construct() {
        $this->euCountries = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL', 'ES', 'FI', 'FR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK', 'GER'];
    }
    public function checkout(Request $request)
    {
        /** @var |App\Models\User $user */
        $user = $request->user();
        $customer = $user->customer;

        if (!$customer->billingAddress || !$customer->shippingAddress) {
            return redirect()->route('profile')->with('error', 'Per favore, fornisci il tuo indirizzo di spedizione');
        }

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $lineItems = [];
        $subtotal = 0;
        $orderItems = [];

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            if ($product->quantity !== null && $product->quantity < $quantity) {
                $message = match ($product->quantity) {
                    0 => "Il Prodotto $product->title è terminato",
                    1 => "È rimasto un solo articolo del Prodotto $product->title",
                    default => "Ci sono solo $product->quantity articoli rimasti per il Prodotto .$product->title",
                };
                return redirect()->back()->with('error', $message);
            }

            $productTotal = $product->price * $quantity;
            $subtotal += $productTotal;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => $product->image ? [$product->image] : [],
                    ],
                    'unit_amount' => $this->isVatExempt($customer) ? round(($product->price * 100) / 1.22) : $product->price * 100,
                ],
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ];

            // Update product quantity
            $product->quantity -= $quantity;
            if ($product->quantity < 0) {
                throw new \Exception("Quantità non sufficiente per il prodotto $product->title.");
            }
            $product->save();
        }

        $shippingCost = $this->calculateShippingCost($customer->shippingAddress->country_code);

        // Applica lo sconto se presente
        $discountCode = $request->input('discount_code');
        $discount = 0;
        if ($discountCode) {
            $discountModel = DiscountCode::where('code', $discountCode)
                ->where('is_active', true)
                ->first();
            if ($discountModel) {
                $discount = round(($subtotal * $discountModel->percentage) / 100, 2);
                $discount = min($discount, $subtotal); // Lo sconto non può superare il subtotale
                $subtotal -= $discount;
            }
        }

        // Aggiungi le spese di spedizione dopo aver applicato lo sconto
        $this->isVatExempt($customer) ? $totalPrice = ($subtotal + $shippingCost) / 1.22 : $totalPrice = $subtotal + $shippingCost;
        

        

        // Aggiungiamo le spese di spedizione come un elemento separato in Stripe
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Spese di spedizione',
                ],
                'unit_amount' => $this->isVatExempt($customer) ? round(($shippingCost * 100) / 1.22) : $shippingCost * 100,
            ],
            'quantity' => 1,
        ];

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
            'discounts' => $discount > 0 ? [
                ['coupon' => $this->createStripeCoupon(round($discount * 100))]
            ] : [],
        ]);

        DB::beginTransaction();
        try {
            $orderData = [
                'total_price' => $this->isVatExempt($customer) ? round($subtotal / 1.22) : $subtotal,
                'status' => OrderStatus::Unpaid,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'shipping_cost' => $this->isVatExempt($customer) ? round($shippingCost / 1.22) : $shippingCost,
                'discount' => $discount,
            ];

            $order = Order::create($orderData);

            // Create Order Items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // Create Payment
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'status' => PaymentStatus::Pending,
                'type' => 'cc',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'session_id' => $checkout_session->id,
            ];

            Payment::create($paymentData);

            DB::commit();

            CartItem::where('user_id', $user->id)->delete();

            return redirect($checkout_session->url);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work' . $e->getMessage());
            throw $e;
        }
    }

    private function createStripeCoupon($discountAmountInCents)
    {
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $coupon = $stripe->coupons->create([
            'amount_off' => $discountAmountInCents,
            'currency' => 'eur',
            'duration' => 'once',
        ]);

        return $coupon->id;
    }

    public function success(Request $request) {
        /** @var |App\Models\User $user */
        $user = $request->user();

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');

            $session = \Stripe\Checkout\Session::retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'ID della sessione non valido']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();
            if (!$payment) {
                throw new NotFoundHttpException();
            }
            if ($payment->status === PaymentStatus::Paid->value) {
                $this->updateOrderAndSession($payment);
            }

            $customer = \Stripe\Customer::retrieve($session->customer);

            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
        
    }

    public function failure(Request $request) {
        return view('checkout.failure', ['message' => 'Pagamento non riuscito']);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        /** @var |App\Models\User $user */
        $user = $request->user();
        $customer = $user->customer;

        $lineItems = [];
        $subtotal = 0;

        foreach ($order->items as $item) {
            $subtotal += $this->isVatExempt($customer) ? ($item->unit_price * $item->quantity) / 1.22 : $item->unit_price * $item->quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product->title,
                        //'images' => [$item->product->image]
                    ],
                    'unit_amount' => $this->isVatExempt($customer) ? round(($item->unit_price * 100) / 1.22) : $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Calcola lo sconto
        $discountAmount = 0;
        if ($order->discount > 0) {
            $discountAmount = min($subtotal, $order->discount); // Lo sconto non può superare il subtotale
            $subtotal -= $discountAmount;
        }

        // Aggiungi le spese di spedizione dopo aver applicato lo sconto
        $totalAmount = $subtotal + $order->shipping_cost;

        // Aggiungi le spese di spedizione come elemento separato
        if ($order->shipping_cost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Spese di spedizione',
                    ],
                    'unit_amount' => $order->shipping_cost * 100,
                ],
                'quantity' => 1,
            ];
        }

        // Prepara l'array di discounts se c'è uno sconto
        $discounts = [];
        if ($discountAmount > 0) {
            $discountInCents = round($discountAmount * 100);
            $discounts[] = ['coupon' => $this->createStripeCoupon($discountInCents)];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
            'discounts' => $discounts,
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->amount = $totalAmount; // Salva l'importo totale in euro
        $order->payment->save();

        return redirect($session->url);
    }

    public function webhooK() {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $endpoint_secret = env('WEBHOOK_SECRET_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $sessionId = $session->id;

                $payment = Payment::where('session_id', $sessionId)->first();
                if ($payment) {
                    $order = $payment->order;
                    $order->update(['status' => OrderStatus::Paid]);
                    $payment->update([
                        'status' => PaymentStatus::Paid,
                        'total_amount' => $session->amount_total / 100, // Aggiorna il totale pagato
                    ]);
                }
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }

    private function updateOrderAndSession(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->status = PaymentStatus::Paid->value;
            $payment->update();

            $order = $payment->order;

            $order->load(['user.customer.billingAddress', 'user.customer.shippingAddress']);

            $order->status = OrderStatus::Paid->value;
            $order->update();

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work' . $e->getMessage());
            throw $e;
        }
        
        try {
            $adminUsers = User::where('is_admin', 1)->get();
            $allUsers = collect([...$adminUsers, $order->user])->unique('id');
            foreach ($allUsers as $user) {
                Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
            }
        } catch (\Exception $e) {
            Log::critical('Error sending email: ' . $e->getMessage());
        }
    }

    public function calculateShippingCost($countryCode)
    {
        $shippingCost = ShippingCost::where('country_code', $countryCode)->first();
        return $shippingCost ? $shippingCost->cost : 0;
    }

    private function isVatExempt($customer) {
        return !empty($customer->vat_number) && strtoupper($customer->vat_country_code) !== 'IT' || !in_array(strtoupper($customer->billingAddress->country_code), $this->euCountries);
    }
}
