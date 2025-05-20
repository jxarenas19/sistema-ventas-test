<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Application\UseCases\CreateOrderUseCase;
use App\Filament\Resources\OrderResource;
use App\Infrastructure\Persistence\EloquentOrderRepository;
use App\Infrastructure\Services\StockService;
//use App\Services\OrderService;
use App\Models\Order;
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
        $useCase = new CreateOrderUseCase(
            new EloquentOrderRepository(),
            new StockService()
        );

        $orderId = $useCase->execute($data);
        return Order::findOrFail($orderId);
    }

//    public function mutateFormDataBeforeCreate(array $data): array
//    {
//        $data = $this->data;
//
//        $data['items'] = collect($data['items'] ?? [])
//            ->filter(fn($item) => is_array($item))
//            ->values()
//            ->all();
//
//        $data['user_id'] = auth()->id();
//        $useCase = new CreateOrderUseCase(
//            new EloquentOrderRepository(),
//            new StockService()
//        );
//
//        $useCase->execute($data);
//        return [];
//    }




}
