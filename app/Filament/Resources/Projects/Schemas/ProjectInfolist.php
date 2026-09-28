<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('concept')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('narrative')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('cover_image')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('opens_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('closes_at')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('is_preorder')
                    ->boolean(),
                TextEntry::make('preorder_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('show_countdown')
                    ->boolean(),
                TextEntry::make('purchase_limit')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
