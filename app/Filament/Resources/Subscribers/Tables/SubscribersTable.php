<?php

namespace App\Filament\Resources\Subscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')->searchable(),
                TextColumn::make('source')->label('Sumber'),
                TextColumn::make('subscribed_at')->label('Daftar')->dateTime('j M Y, H.i')->sortable(),
                TextColumn::make('unsubscribed_at')->label('Berhenti')->dateTime('j M Y, H.i')->placeholder('-'),
            ])
            ->defaultSort('subscribed_at', 'desc')
            ->filters([
                Filter::make('active')
                    ->label('Masih berlangganan')
                    ->query(fn ($query) => $query->whereNull('unsubscribed_at'))
                    ->default(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}