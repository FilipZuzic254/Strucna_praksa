<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(200),

                Textarea::make('description')
                    ->nullable()
                    ->maxLength(1000)
                    ->autosize(),

                Repeater::make('images')
                    ->relationship('images')
                    ->reorderable('sort_order')
                    ->reorderableWithDragAndDrop()
                    ->schema([
                        TextInput::make('title')
                            ->nullable()
                            ->maxLength(255),
                        FileUpload::make('image_path')
                            ->image()
                            ->disk('public')
                            ->directory(function ($livewire) {
                                $galleryName = $livewire->data['name'] ?? 'unknown';
                                return 'gallery/' . str($galleryName)->slug();
                            })
                            ->required(),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Add Image')
                    ->columnSpanFull(),
            ]);
    }
}
