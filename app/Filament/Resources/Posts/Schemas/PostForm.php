<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Image')
                    ->nullable()
                    ->image()
                    ->disk('public')
                    ->directory('posts')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->string()
                    ->maxLength(255),
                Select::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                RichEditor::make('content')
                    ->label('Content')
                    ->nullable()
                    ->maxLength(65535)
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('posts/content')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->default('draft'),
            ]);
    }
}
