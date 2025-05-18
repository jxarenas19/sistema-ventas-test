<?php

namespace App\Actions;

use App\Models\Product;
use RuntimeException;

class DecreaseStock
{
    public function __construct(private int $productId, private int $amount) {}

    public function execute(): void
    {
        $product = Product::where('id', $this->productId)->lockForUpdate()->first();

        if ($product->stock < $this->amount) {
            throw new RuntimeException("Stock insuficiente para {$product->name}");
        }

        $product->decrement('stock', $this->amount);
    }
}
