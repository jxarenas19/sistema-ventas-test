<?php

namespace App\Infrastructure\Services;

use App\Domain\Ports\StockServiceInterface;
use App\Models\Product;
use App\Domain\Entities\Product as DomainProduct;

class StockService implements StockServiceInterface
{
    public function decrease(int $productId, int $quantity): void
    {
        $productEloquent = Product::findOrFail($productId);

        $domainProduct = new DomainProduct($productEloquent->stock);
        $domainProduct->decreaseStock($quantity);

        $productEloquent->update(['stock' => $domainProduct->getStock()]);
    }
}
