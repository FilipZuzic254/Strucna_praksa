<?php

namespace App\Filament\Resources\Documents\Tables;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file_name')
                    ->searchable(),
                TextColumn::make('file_path')
                    ->label('Preview document')
                    ->state('Open')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'pdf' => 'PDF',
                        'doc' => 'Word/doc',
                        'docx' => 'Word/docx',
                        'xls' => 'Excel/xls',
                        'xlsx' => 'Excel/xlsx',
                        'jpg' => 'JPG',
                        'png' => 'PNG',
                        'txt' => 'Text',
                    ])
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->where(function ($q) use ($data) {
                                foreach ($data['values'] as $type) {
                                    $q->orWhere('file_name', 'like', '%.' . $type);
                                }
                            });
                        }
                    }),
            ])
            ->recordUrl(fn ($record) => asset('storage/' . $record->file_path))
            ->recordActions([
                DeleteAction::make()
                    ->before(function ($record) {
                        Storage::disk('public')->delete($record->file_path);
                    }),
            ])
            ->toolbarActions([
            ])
            ->striped();
    }
}
