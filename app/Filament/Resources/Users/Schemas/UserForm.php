<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->string()
                    ->maxLength(255),
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->email()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('Password')
                    ->required(fn(string $context): bool => $context === 'create')
                    ->password()
                    ->string()
                    ->minLength(6)
                    ->confirmed()
                    ->revealable()
                    ->autocomplete('new-password')
                    ->dehydrated(fn($state) => !empty($state)),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->required(fn(string $context): bool => $context === 'create')
                    ->password()
                    ->string()
                    ->minLength(6)
                    ->revealable()
                    ->dehydrated(fn($state) => !empty($state)),
            ]);
    }
}
