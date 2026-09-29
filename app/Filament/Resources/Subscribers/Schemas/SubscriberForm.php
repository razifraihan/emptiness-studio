<?php

namespace App\Filament\Resources\Subscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TextInput::make('source')->label('Sumber'),
            DateTimePicker::make('unsubscribed_at')->label('Berhenti berlangganan pada'),
        ]);
    }
}