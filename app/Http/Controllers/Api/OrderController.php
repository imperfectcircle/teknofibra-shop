<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Mail\OrderUpdateEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderListResource;

class OrderController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Order::query()
            ->withCount('items')
            ->with('user.customer')
            ->where('id', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return OrderListResource::collection($query);
    }

    public function view(Order $order)
    {
        $order->load('items.product');
        return new OrderResource($order);
    }

    public function getStatuses() {
        return OrderStatus::getStatuses();
    }

    public function changeStatus(Order $order, $status)
    {
        DB::beginTransaction();
        try {
            $order->status = $status;
            $order->save();

            if ($status === OrderStatus::Cancelled->value) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product && $product->quantity !== null) {
                        $product->quantity += $item->quantity;
                        $product->save();
                    }
                }
            }
            Mail::to($order->user)->send(new OrderUpdateEmail($order));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();


        return response('', 200);
    }
}
