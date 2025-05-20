<?php

namespace App\Domain\Ports;

use App\Domain\Entities\Order;

interface OrderRepositoryInterface
{
    public function save(Order $order): int;

    public function update(int $id, array $fields): void;



}
