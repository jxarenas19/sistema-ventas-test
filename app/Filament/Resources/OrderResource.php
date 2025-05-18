<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = \App\Models\Product::find($state);
                                if ($product) {
                                    $set('price', $product->price);
                                    $set('total', $product->price);
                                }
                            }),

                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $product = \App\Models\Product::find($get('product_id'));
                                if ($product) {
                                    $set('total', $get('price')*$get('quantity'));
                                }
                            }),

                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                if ($get('price')) {
                                    $set('total', $get('price')*$get('quantity'));
                                }
                            }),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->disabled()
                            ->dehydrated(false)
                            ->numeric()
                            ->formatStateUsing(function ($state, callable $get) {
                                $price = floatval($get('price') ?? 0);
                                $qty = intval($get('quantity') ?? 0);
                                return $price * $qty;
                            })
                    ])
                    ->columns(3)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Orden')
                    ->formatStateUsing(fn ($state) => 'Order ' . $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_summary')
                    ->label('Productos')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(fn ($item) => "{$item->product->name} ({$item->quantity})")->join(', ');
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->getStateUsing(function ($record) {
                        return $record->items->sum(fn ($item) => $item->price * $item->quantity);
                    })
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('items.product');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
