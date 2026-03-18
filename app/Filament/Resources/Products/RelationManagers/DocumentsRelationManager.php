<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Documents\DocumentResource;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                TextColumn::make('file_name'),
                TextColumn::make('file_path')
                    ->label('Preview document')
                    ->state('Open')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->filters([
            ])
            ->headerActions([
                CreateAction::make()
                    ->url(fn () =>
                        DocumentResource::getUrl(
                            'create'
                        )
                    ),
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
