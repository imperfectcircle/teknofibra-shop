<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Http\Helpers\CartHelper as Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;

class CheckoutController extends Controller
{
    public function checkout(Request $request) {
        /** @var |App\Models\User $user */
        $user = $request->user();

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $product->title,
                    'images' => [$product->image],
                ],
                'unit_amount_decimal' => $product->price * 100,
                ],
                'quantity' => $quantity,
            ];
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        $order = Order::create($orderData);

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

        return redirect($checkout_session->url);
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
            $payment = Payment::query()->where(['session_id' => $session_id, 'status' => PaymentStatus::Pending])->first();
            if (!$payment) {
                return view('checkout.failure', ['message' => 'Pagamento non trovato']);
            }
            $payment->status = PaymentStatus::Paid;
            $payment->update();

            $order = $payment->order;
            $order->status = OrderStatus::Paid;
            $order->update();

            CartItem::where('user_id', $user->id)->delete();
                
            $customer = \Stripe\Customer::retrieve($session->customer);

            return view('checkout.success', compact('customer'));
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
        
    }

    public function failure(Request $request) {
        dd($request->all());
    }
}
