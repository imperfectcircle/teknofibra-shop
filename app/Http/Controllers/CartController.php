<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Helpers\CartHelper as Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $total = 0;
        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['quantity'];
        }

        $shippingCost = 0;
        $user = $request->user();
        if ($user) {
            $shippingAddress = $user->customer->shippingAddress;
            if ($shippingAddress) {
                $shippingCost = $this->calculateShippingCost($shippingAddress->country_code);
            }
        }

    $total += $shippingCost;

        return view('cart.index', compact('cartItems', 'products', 'total', 'shippingCost'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        $shippingCost = 0;
        if ($user) {
            $shippingAddress = $user->customer->shippingAddress;
            if ($shippingAddress) {
                $shippingCost = $this->calculateShippingCost($shippingAddress->country_code);
            }
        }

        $totalQuantity = 0;

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $totalQuantity = $cartItem->quantity + $quantity;
            } else {
                $totalQuantity = $quantity;
            }
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $totalQuantity = $item['quantity'] + $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $totalQuantity = $quantity;
            }
        }

        if ($product->quantity !== null && $product->quantity < $totalQuantity) {
            return response([
                'message' => match ( $product->quantity ) {
                    0 => 'Questo Prodotto è attualmente esaurito',
                    1 => 'È possibile aggiungere un solo Prodotto',
                    default => 'È possibile aggiungere solo ' . $product->quantity . ' Prodotti'
                }
            ], 422);
        }

        if ($user) {

            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->update();
            } else {
                $data = [
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];
                CartItem::create($data);
            }

            return response([
                'count' => Cart::getCartItemsCount(),
                'shippingCost' => $shippingCost
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response([
                'count' => Cart::getCountFromItems($cartItems), 'shippingCost' => $shippingCost]);
        }
    }

    public function remove(Request $request, Product $product)
    {
        $user = $request->user();

        $shippingCost = 0;
        if ($user) {
            $shippingAddress = $user->customer->shippingAddress;
            if ($shippingAddress) {
                $shippingCost = $this->calculateShippingCost($shippingAddress->country_code);
            }
        }

        if ($user) {
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->delete();
            }

            return response([
                'count' => Cart::getCartItemsCount(),
                'shippingCost' => $shippingCost
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response([
                'count' => Cart::getCountFromItems($cartItems),
                'shippingCost' => $shippingCost
            ]);
        }
    }

    public function updateQuantity(Request $request, Product $product)
    {
        $quantity = (int)$request->post('quantity');
        $user = $request->user();

        $shippingCost = 0;
        if ($user) {
            $shippingAddress = $user->customer->shippingAddress;
            if ($shippingAddress) {
                $shippingCost = $this->calculateShippingCost($shippingAddress->country_code);
            }
        }

        if ($product->quantity !== null && $product->quantity < $quantity) {
            return response([
                'message' => match ( $product->quantity ) {
                    0 => 'Questo Prodotto è attualmente esaurito',
                    1 => 'È possibile aggiungere un solo Prodotto',
                    default => 'È possibile aggiungere solo ' . $product->quantity . ' Prodotti'
                }
            ], 422);
        }

        if ($user) {
            CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);

            return response([
                'count' => Cart::getCartItemsCount(),
                'shippingCost' => $shippingCost
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response([
                'count' => Cart::getCountFromItems($cartItems),
                'shippingCost' => $shippingCost
            ]);
        }
    }

    public function calculateShippingCost($countryCode)
    {
        $shippingCost = ShippingCost::where('country_code', $countryCode)->first();
        return $shippingCost ? $shippingCost->cost : 0;
    }

    public function checkoutOrder(Order $order)
    {
        if ($order->isPaid()) {
            return redirect()->back()->with('error', 'Questo ordine è già stato pagato.');
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product->title,
                        'images' => $item->product->image ? [$item->product->image] : [],
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

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

        // Aggiorna il session_id dell'ordine
        $order->payment->update(['session_id' => $checkout_session->id]);

        return redirect($checkout_session->url);
    }
}
