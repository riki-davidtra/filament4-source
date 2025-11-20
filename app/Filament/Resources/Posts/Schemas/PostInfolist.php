<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->placeholder('No image available.')
                    ->columnSpanFull(),
                TextEntry::make('title')
                    ->label('Title'),
                TextEntry::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->separator(', '),

                Fieldset::make('Content')
                    ->schema([
                        TextEntry::make('content')
                            ->hiddenLabel()
                            ->markdown()
                            ->placeholder('No content available.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'draft'     => 'gray',
                        'published' => 'success',
                        'archived'  => 'danger',
                        default     => 'gray',
                    }),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}
