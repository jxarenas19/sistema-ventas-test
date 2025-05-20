<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Order;
use App\Domain\Ports\OrderRepositoryInterface;
use App\Domain\Ports\StockServiceInterface;

class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private StockServiceInterface $stockService
    ) {}

    public function execute(array $data): int
    {
        $order = new Order();
        $order->addData(['user_id' => $data["user_id"]]);
        foreach ($data['items'] as $item) {
            $order->addItem($item);
            $this->stockService->decrease($item['product_id'], $item['quantity']);
        }
        $total = $order->getTotal();

        $order_id = $this->orderRepository->save($order);
        $this->orderRepository->update($order_id, ['total' => $total]);

        return $order_id;
    }
}
