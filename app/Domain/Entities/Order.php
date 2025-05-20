<?php

namespace App\Domain\Entities;

class Order
{
    public array $items = [];
    public array $data = [];

    public function addItem(array $item): void
    {
        $this->items[] = $item;
    }

    public function addData(array $data): void
    {
        $this->data = $data;

    }

    public function getTotal(): float
    {
        return array_reduce($this->items, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
