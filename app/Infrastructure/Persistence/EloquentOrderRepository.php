<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Order as DomainOrder;
use App\Domain\Ports\OrderRepositoryInterface;
use App\Models\Order;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function save(DomainOrder $domainOrder): int
    {
        $order = Order::create($domainOrder->data);
//        foreach ($domainOrder->items as $item) {
//            $order->items()->create($item);
//        }
        return $order->id;
    }

    public function update(int $id, array $fields): void
    {
        $order = Order::findOrFail($id);
        $order->update($fields);
    }
}
