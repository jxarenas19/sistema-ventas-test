<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Services\OrderService;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data = $this->data;

        $data['items'] = collect($data['items'] ?? [])
            ->filter(fn($item) => is_array($item))
            ->values()
            ->all();

        $data['user_id'] = auth()->id();
        $order = OrderService::create($data);
        return $order;
    }




}
