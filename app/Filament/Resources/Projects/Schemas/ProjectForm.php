<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('concept')
                    ->columnSpanFull(),
                Textarea::make('narrative')
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->image(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('opens_at'),
                DateTimePicker::make('closes_at'),
                Toggle::make('is_preorder')
                    ->required(),
                Textarea::make('preorder_note')
                    ->columnSpanFull(),
                Toggle::make('show_countdown')
                    ->required(),
                TextInput::make('purchase_limit')
                    ->numeric(),
            ]);
    }
}
