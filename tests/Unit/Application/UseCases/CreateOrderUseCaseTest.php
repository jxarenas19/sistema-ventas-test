<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\CreateOrderUseCase;
use App\Domain\Entities\Order;
use App\Domain\Ports\OrderRepositoryInterface;
use App\Domain\Ports\StockServiceInterface;
use PHPUnit\Framework\TestCase;

class FakeStockService implements StockServiceInterface
{
    public array $calls = [];

    public function decrease(int $productId, int $quantity): void
    {
        $this->calls[] = compact('productId', 'quantity');
    }
}

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    public array $saved = [];
    public array $updated = [];

    public function save(Order $order): int
    {
        $id = count($this->saved) + 1;
        $this->saved[$id] = $order;
        return $id;
    }

    public function update(int $id, array $fields): void
    {
        $this->updated[$id] = $fields;
    }
}

class CreateOrderUseCaseTest extends TestCase
{
    public function test_it_creates_an_order_and_decreases_stock()
    {
        $repo = new InMemoryOrderRepository();
        $stockService = new FakeStockService();

        $useCase = new CreateOrderUseCase($repo, $stockService);

        $orderId = $useCase->execute([
            'user_id' => 1,
            'items' => [
                ['product_id' => 100, 'quantity' => 2,'price'=>10],
                ['product_id' => 101, 'quantity' => 3,'price'=>20]
            ]
        ]);

        $this->assertArrayHasKey($orderId, $repo->saved);
        $this->assertEquals([
            ['productId' => 100, 'quantity' => 2],
            ['productId' => 101, 'quantity' => 3],
        ], $stockService->calls);
        $this->assertEquals($repo->updated[$orderId]['total'], $repo->saved[$orderId]->getTotal());
    }
}
