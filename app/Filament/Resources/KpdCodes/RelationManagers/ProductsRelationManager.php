<?php

namespace App\Filament\Resources\KpdCodes\RelationManagers;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $relatedResource = ProductResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('info')
                    ->icon('heroicon-o-information-circle')
                    ->iconButton()
                    ->tooltip('To edit product KPD code, please edit the product itself.')
                    ->color('warning'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => 
                        ProductResource::getUrl(
                            'edit',
                            ['record' => $record->id]
                        )
                    )
            ]);
    }
}
