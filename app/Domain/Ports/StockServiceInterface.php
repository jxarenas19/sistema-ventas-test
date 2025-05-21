<?php

namespace App\Domain\Ports;

interface StockServiceInterface
{
    public function decrease(int $productId, int $quantity): void;

}
