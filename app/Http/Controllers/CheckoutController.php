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
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\CartHelper as Cart;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request) {
        /** @var |App\Models\User $user */
        $user = $request->user();

        $customer = $user->customer;
        if (!$customer->billingAddress || !$customer->shippingAddress) {
            return redirect()->route('profile')->with('error', 'Per favore, fornisci il tuo indirizzo di spedizione');
        } 

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            if ($product->quantity !== null && $product->quantity < $quantity) {
                $message = match ($product->quantity) {
                    0 => 'Il Prodotto "'.$product->title.'" è terminato',
                    1 => 'È rimasto un solo articolo del Prodotto "'.$product->title,
                    default => 'Ci sono solo ' . $product->quantity . ' articoli rimasti per il Prodotto "'.$product->title,
                };
                return redirect()->back()->with('error', $message);
            }
        }

        DB::beginTransaction();
        try {

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $product->title,
                    'images' => $product->image ? [$product->image] : [],
                ],
                'unit_amount_decimal' => $product->price * 100,
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
                throw new \Exception('Quantità non sufficiente per il prodotto "'.$product->title.'"');
            }
            $product->save();
        }

        $shippingCost = $this->calculateShippingCost($customer->shippingAddress->country_code);
        

        // Aggiungiamo le spese di spedizione come un elemento separato in Stripe
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Spese di spedizione',
                ],
                'unit_amount_decimal' => $shippingCost * 100,
            ],
            'quantity' => 1,
        ];

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

            $orderData = [
                'total_price' => $totalPrice, // Questo dovrebbe essere solo il totale dei prodotti
                'status' => OrderStatus::Unpaid,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'shipping_cost' => $shippingCost,
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

    public function checkoutOrder(Order $order, Request $request) {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product->title,
                        //'images' => [$item->product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

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

        $totalAmount = $order->total_price + $order->shipping_cost;

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->amount = $totalAmount;
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

    public function sendcloud() {
        return response('', 200);
    }

    public function calculateShippingCost($countryCode)
    {
        $shippingCost = ShippingCost::where('country_code', $countryCode)->first();
        return $shippingCost ? $shippingCost->cost : 0;
    }
}
