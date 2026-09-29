<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Nomor')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('customer_name')
                    ->label('Pembeli')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Order::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid', 'packed' => 'info',
                        'shipped', 'done' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Order::PAYMENT_STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H.i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Order::STATUSES),
                SelectFilter::make('payment_status')
                    ->label('Pembayaran')
                    ->options(Order::PAYMENT_STATUSES),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}