<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Varian';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sku')
                ->label('SKU')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('size')->label('Ukuran'),
            TextInput::make('color')->label('Warna'),
            TextInput::make('price')
                ->label('Harga')
                ->required()
                ->numeric()
                ->minValue(0)
                ->prefix('Rp'),
            TextInput::make('stock')
                ->label('Stok')
                ->required()
                ->numeric()
                ->minValue(0)
                ->default(0),
            TextInput::make('reserved')
                ->label('Terkunci (reserved)')
                ->numeric()
                ->default(0)
                ->disabled()
                ->dehydrated(false)
                ->helperText('Hanya berubah lewat proses pembayaran.'),
            TextInput::make('weight_grams')
                ->label('Berat (gram)')
                ->required()
                ->numeric()
                ->minValue(0)
                ->default(300),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('size')->label('Ukuran'),
                TextColumn::make('color')->label('Warna'),
                TextColumn::make('price')->label('Harga')->money('IDR', locale: 'id', decimalPlaces: 0),TextColumn::make('stock')->label('Stok'),
                TextColumn::make('reserved')->label('Terkunci'),
                TextColumn::make('weight_grams')->label('Berat (g)'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}