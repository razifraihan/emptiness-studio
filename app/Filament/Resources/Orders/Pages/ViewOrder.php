<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('setShipping')
                ->label('Atur ongkir')
                ->color('gray')
                ->visible(fn (Order $record): bool => $record->status === 'pending' && $record->payment_status === 'unpaid')
                ->fillForm(fn (Order $record): array => [
                    'shipping_cost' => $record->shipping_cost,
                    'courier' => $record->courier,
                    'courier_service' => $record->courier_service,
                ])
                ->schema([
                    TextInput::make('shipping_cost')
                        ->label('Ongkos kirim')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix('Rp'),
                    TextInput::make('courier')->label('Kurir'),
                    TextInput::make('courier_service')->label('Layanan'),
                ])
                ->action(function (array $data, Order $record): void {
                    $shipping = (int) $data['shipping_cost'];

                    $record->update([
                        'shipping_cost' => $shipping,
                        'courier' => $data['courier'] ?: null,
                        'courier_service' => $data['courier_service'] ?: null,
                        'total' => $record->subtotal - $record->discount + $shipping,
                    ]);

                    Notification::make()->title('Ongkir diperbarui')->success()->send();
                }),

            Action::make('markPaid')
                ->label('Tandai lunas')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Stok yang dikunci akan dipotong dari stok produk.')
                ->visible(fn (Order $record): bool => $record->status === 'pending' && $record->payment_status === 'unpaid')
                ->action(function (Order $record): void {
                    $ok = $record->markPaid();

                    Notification::make()
                        ->title($ok ? 'Pesanan ditandai lunas' : 'Pesanan tidak bisa ditandai lunas')
                        ->color($ok ? 'success' : 'danger')
                        ->send();
                }),

            Action::make('pack')
                ->label('Tandai dikemas')
                ->visible(fn (Order $record): bool => $record->status === 'paid')
                ->action(function (Order $record): void {
                    $record->update(['status' => 'packed']);

                    Notification::make()->title('Pesanan sedang dikemas')->success()->send();
                }),

            Action::make('ship')
                ->label('Kirim & isi resi')
                ->color('info')
                ->visible(fn (Order $record): bool => in_array($record->status, ['paid', 'packed']))
                ->fillForm(fn (Order $record): array => [
                    'courier' => $record->courier,
                    'courier_service' => $record->courier_service,
                    'tracking_number' => $record->tracking_number,
                ])
                ->schema([
                    TextInput::make('courier')->label('Kurir'),
                    TextInput::make('courier_service')->label('Layanan'),
                    TextInput::make('tracking_number')->label('Nomor resi')->required(),
                ])
                ->action(function (array $data, Order $record): void {
                    $record->update([
                        'status' => 'shipped',
                        'courier' => $data['courier'] ?: null,
                        'courier_service' => $data['courier_service'] ?: null,
                        'tracking_number' => $data['tracking_number'],
                    ]);

                    Notification::make()->title('Pesanan ditandai dikirim')->success()->send();
                }),

            Action::make('done')
                ->label('Tandai selesai')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Order $record): bool => $record->status === 'shipped')
                ->action(function (Order $record): void {
                    $record->update(['status' => 'done']);

                    Notification::make()->title('Pesanan selesai')->success()->send();
                }),

            Action::make('internalNote')
                ->label('Catatan internal')
                ->color('gray')
                ->fillForm(fn (Order $record): array => ['internal_note' => $record->internal_note])
                ->schema([
                    Textarea::make('internal_note')->label('Catatan internal')->rows(4),
                ])
                ->action(function (array $data, Order $record): void {
                    $record->update(['internal_note' => $data['internal_note'] ?: null]);

                    Notification::make()->title('Catatan disimpan')->success()->send();
                }),

            Action::make('cancelOrder')
                ->label('Batalkan pesanan')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('Stok yang dikunci akan dilepas kembali.')
                ->visible(fn (Order $record): bool => $record->status === 'pending' && $record->payment_status === 'unpaid')
                ->action(function (Order $record): void {
                    $ok = $record->cancelUnpaid();

                    Notification::make()
                        ->title($ok ? 'Pesanan dibatalkan' : 'Pesanan tidak bisa dibatalkan')
                        ->color($ok ? 'success' : 'danger')
                        ->send();
                }),
        ];
    }
}