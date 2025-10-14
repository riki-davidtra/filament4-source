<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $user         = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');

        return $schema
            ->components([
                FileUpload::make('avatar_url')
                    ->label('Photo')
                    ->nullable()
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
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
                    ->regex('/^[a-zA-Z0-9._]+$/') // only letters, numbers, periods, underscores
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
                Select::make('roles')
                    ->label('Roles')
                    ->nullable()
                    ->multiple()
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query)  use ($isSuperAdmin) {
                            if (!$isSuperAdmin) {
                                $query->where('name', '!=', 'super_admin');
                            }
                        }
                    )
                    ->preload()
                    ->searchable(),
            ]);
    }
}
