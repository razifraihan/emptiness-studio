<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi produk')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('project_id')
                            ->label('Project')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Aktif',
                                'archived' => 'Diarsipkan',
                            ])
                            ->required()
                            ->default('draft'),
                        Toggle::make('is_featured')
                            ->label('Tampilkan di beranda')
                            ->default(false),
                    ]),

                Section::make('Deskripsi')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4),
                        Textarea::make('material')
                            ->label('Bahan dan perawatan')
                            ->rows(3),
                    ]),

                Section::make('Size chart')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        Repeater::make('size_chart')
                            ->label('')
                            ->columns(3)
                            ->addActionLabel('Tambah ukuran')
                            ->schema([
                                TextInput::make('size')->label('Ukuran')->required(),
                                TextInput::make('chest_cm')->label('Lebar dada (cm)')->numeric(),
                                TextInput::make('length_cm')->label('Panjang (cm)')->numeric(),
                            ]),
                    ]),
            ]);
    }
}