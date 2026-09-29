<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pesanan')
                ->columns(3)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('code')->label('Nomor')->copyable(),
                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => Order::STATUSES[$state] ?? $state)
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'paid', 'packed' => 'info',
                            'shipped', 'done' => 'success',
                            default => 'gray',
                        }),
                    TextEntry::make('payment_status')
                        ->label('Pembayaran')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => Order::PAYMENT_STATUSES[$state] ?? $state)
                        ->color(fn (string $state): string => match ($state) {
                            'paid' => 'success',
                            'unpaid' => 'warning',
                            default => 'gray',
                        }),
                    TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y, H.i'),
                    TextEntry::make('paid_at')->label('Dibayar')->dateTime('d M Y, H.i')->placeholder('-'),
                    TextEntry::make('expires_at')->label('Batas bayar')->dateTime('d M Y, H.i')->placeholder('-'),
                ]),

            Section::make('Pembeli')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('customer_name')->label('Nama'),
                    TextEntry::make('customer_email')->label('Email')->copyable(),
                    TextEntry::make('customer_phone')
                        ->label('WhatsApp')
                        ->url(function (Order $record): string {
                            $digits = preg_replace('/\D+/', '', $record->customer_phone);

                            if (str_starts_with($digits, '0')) {
                                $digits = '62' . substr($digits, 1);
                            }

                            return 'https://wa.me/' . $digits;
                        })
                        ->openUrlInNewTab(),
                    TextEntry::make('address')
                        ->label('Alamat')
                        ->state(fn (Order $record): string => "{$record->address}, {$record->district}, {$record->city}, {$record->province} {$record->postal_code}"),
                ]),

            Section::make('Item')
                ->columnSpanFull()
                ->schema([
                    RepeatableEntry::make('items')
                        ->hiddenLabel()
                        ->columns(5)
                        ->schema([
                            TextEntry::make('product_name')->label('Produk'),
                            TextEntry::make('variant_label')->label('Varian')->placeholder('-'),
                            TextEntry::make('sku')->label('SKU'),
                            TextEntry::make('quantity')->label('Jumlah'),
                            TextEntry::make('line_total')
                                ->label('Subtotal')
                                ->state(fn ($record): int => $record->price * $record->quantity)
                                ->money('IDR', locale: 'id', decimalPlaces: 0),
                        ]),
                ]),

            Section::make('Biaya')
                ->columns(4)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('subtotal')->label('Subtotal')->money('IDR', locale: 'id', decimalPlaces: 0),
                    TextEntry::make('shipping_cost')->label('Ongkir')->money('IDR', locale: 'id', decimalPlaces: 0),
                    TextEntry::make('discount')->label('Diskon')->money('IDR', locale: 'id', decimalPlaces: 0),
                    TextEntry::make('total')->label('Total')->money('IDR', locale: 'id', decimalPlaces: 0)->weight('bold'),
                ]),

            Section::make('Pengiriman')
                ->columns(3)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('courier')->label('Kurir')->placeholder('-'),
                    TextEntry::make('courier_service')->label('Layanan')->placeholder('-'),
                    TextEntry::make('tracking_number')->label('Nomor resi')->placeholder('-')->copyable(),
                ]),

            Section::make('Catatan')
                ->columns(3)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('note')->label('Dari pembeli')->placeholder('-'),
                    TextEntry::make('gift_note')->label('Pesan hadiah')->placeholder('-'),
                    TextEntry::make('internal_note')->label('Catatan internal')->placeholder('-'),
                ]),
        ]);
    }
}