<?php

namespace App\Filament\Resources\StaticPages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
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
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft (tidak tampil)',
                            'published' => 'Terbit',
                        ])
                        ->required()
                        ->default('draft'),
                    TextInput::make('intro')
                        ->label('Kalimat pembuka (opsional)')
                        ->maxLength(255),
                    Textarea::make('body')
                        ->label('Isi')
                        ->rows(18)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}