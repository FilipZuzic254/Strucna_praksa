<?php

namespace App\Livewire;

use App\Models\ProductDocument;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ItemDocuments extends TableWidget
{
    public ?int $product_id = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ProductDocument::where('product_id', $this->product_id))
            ->columns([
                TextColumn::make('document.file_name')
                    ->label('File Name'),
                TextColumn::make('file_path')
                    ->label('Preview document')
                    ->state('Open')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                TextColumn::make('document.updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->emptyStateHeading('No documents for this item yet')
            ->paginated(false);
    }
}
