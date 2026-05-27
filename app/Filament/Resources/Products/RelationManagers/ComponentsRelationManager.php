<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Components\ComponentResource;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    protected static ?string $relatedResource = ComponentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordActions([
                ViewAction::make(),
                DetachAction::make()
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect(),
            ]);
    }
}
