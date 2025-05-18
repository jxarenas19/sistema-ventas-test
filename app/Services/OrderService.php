<?php

namespace App\Services;

use App\Actions\DecreaseStock;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public static function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create(['user_id' => $data["user_id"]]);
            $total = 0;
            if (empty($data["items"])) {
                throw new \InvalidArgumentException('La orden debe contener al menos un producto.');
            }

            foreach ($data['items'] as $itemData) {
                $item = new OrderItem($itemData);
                $total += $item->price * $item->quantity;

                (new DecreaseStock($item->product_id, $item->quantity))->execute(); // 🔷 Command
            }

            $order->update(['total' => $total]);
            return $order;
        });
    }
}
