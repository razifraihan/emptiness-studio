<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi project')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('number')
                        ->label('Nomor project')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99),
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft (tidak tampil)',
                            'upcoming' => 'Segera hadir',
                            'open' => 'Dibuka',
                            'closed' => 'Ditutup',
                            'archived' => 'Diarsipkan',
                        ])
                        ->required()
                        ->default('draft'),
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
                    FileUpload::make('cover_image')
                        ->label('Foto sampul')
                        ->image()
                        ->disk('public')
                        ->directory('projects')
                        ->columnSpanFull(),
                ]),

            Section::make('Cerita project')
                ->columnSpanFull()
                ->schema([
                    Textarea::make('concept')->label('Konsep')->rows(3),
                    Textarea::make('narrative')->label('Narasi')->rows(8),
                    TextInput::make('key_message')->label('Key message')->maxLength(255),
                ]),

            Section::make('Jadwal dan rilis')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    DateTimePicker::make('opens_at')->label('Buka penjualan'),
                    DateTimePicker::make('closes_at')->label('Tutup penjualan'),
                    Toggle::make('is_preorder')->label('Mode pre-order')->default(false),
                    TextInput::make('preorder_note')->label('Catatan pre-order (estimasi produksi dan kirim)'),
                    Toggle::make('show_countdown')->label('Tampilkan countdown (opsional)')->default(false),
                    TextInput::make('purchase_limit')->label('Batas beli per pelanggan')->numeric()->minValue(1),
                ]),
        ]);
    }
}