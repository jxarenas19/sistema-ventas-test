<?php

namespace App\Domain\Entities;

class Product
{
    private int $stock;

    public function __construct(int $stock)
    {
        $this->stock = $stock;
    }

    public function decreaseStock(int $amount): void
    {
        if ($this->stock < $amount) {
            throw new \DomainException('Stock insuficiente');
        }

        $this->stock -= $amount;
    }

    public function getStock()
    {
        return $this->stock;
    }
}
