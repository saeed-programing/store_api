<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\StoreOrderRequest;
use App\Http\Requests\Api\V1\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\Payment\ZibalService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(private ZibalService $zibal)
    {
    }

    public function store(StoreOrderRequest $request)
    {
        $totalAmount = 0;
        $deliveryAmount = 0;
        foreach ($request->order_items as $orderItem) {
            $product = Product::findOrFail($orderItem['product_id']);
            if ($product->quantity < $orderItem['quantity']) {
                return ApiResponse::errorResponse('quantity', 'This Quantity of products is not available in stock', 422);
            }
            $totalAmount += $product->price * $orderItem['quantity'];
            $deliveryAmount += $product->delivery_amount * $orderItem['quantity'];
        }
        $payableAmount = $totalAmount + $deliveryAmount;

        $res = $this->zibal->send($payableAmount);

        if ($res['result'] !== 100) {
            return ApiResponse::errorResponse('Payment gateway', $res['message'], 500);
        }

        if ($res['result'] === 100) {
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_amount' => $totalAmount,
                'delivery_amount' => $deliveryAmount,
                'payable_amount' => $payableAmount,
            ]);

            foreach ($request->order_items as $orderItem) {
                $product = Product::findOrFail($orderItem['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $orderItem['quantity'],
                    'subtotal' => ($orderItem['quantity'] * $product->price)
                ]);
            }

            Transaction::create([
                'user_id' => $request->user_id,
                'order_id' => $order->id,
                'amount' => $payableAmount,
                'token' => $res['trackId'],
                'request_from' => $request->request_from,
            ]);

            DB::commit();


            return ApiResponse::successResponse([
                'url' => "https://gateway.zibal.ir/start/" . $res['trackId']
            ], 200);
        }

    }

    public function verify(UpdateOrderRequest $request)
    {
        $res = $this->zibal->verify($request->token);
        if ($res['result'] !== 100) {
            return ApiResponse::errorResponse('payment', 'error...', 500);
        } else {
            $transaction = Transaction::where('token', $request->token)->firstOrFail();
            DB::beginTransaction();

            $transaction->update([
                'status' => 1,
                'trans_id' => $request->refNumber,
            ]);
            $order = Order::findOrFail($transaction->order_id);
            $order->update([
                'status' => 'confirmed',
                'payable_status' => 1
            ]);

            foreach (OrderItem::where('order_id', $order->id)->get() as $item) {
                $product = Product::find($item->product_id);
                $product->update([
                    'quantity' => ($product->quantity - $item->quantity)
                ]);
            }

            DB::commit();

            return ApiResponse::successResponse(null, 200, 'complated');
        }


    }
}

