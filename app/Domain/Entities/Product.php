<?php

namespace App\Domain\Entities;

class Product
{
    private int $stock;

    public function __construct(int $stock)
    {
        $this->stock = $stock;
    }

    public function decreaseStock(int $quantity): void
    {
        if ($this->stock < $quantity) {
            throw new \DomainException('Stock insuficiente');
        }

        $this->stock -= $quantity;
    }

    public function getStock()
    {
        return $this->stock;
    }
}
