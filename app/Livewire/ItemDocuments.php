<?php

namespace App\Livewire;

use App\Models\Document;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class ItemDocuments extends TableWidget
{
    public ?int $product_id = null;
    public ?int $inventory_item_id = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                return Document::where('inventory_item_id', $this->inventory_item_id)
                    ->orWhere(function ($query) {
                        $query->whereHas('products', fn ($q) => $q->where('product_id', $this->product_id));
                    });
            })
            ->columns([
                TextColumn::make('file_name')
                    ->searchable(),
                TextColumn::make('file_path')
                    ->label('Preview document')
                    ->state('Open')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'financial' => 'success',
                        'technical' => 'info',
                    })
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('fileType')
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
                SelectFilter::make('type')
                    ->label('Document Use')
                    ->options([
                        'financial' => 'Financial',
                        'technical' => 'Technical',
                    ])
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereIn('type', $data['values']);
                        }
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Financial Document')
                    ->schema([
                        TextInput::make('file_name')
                            ->label('File Name')
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('Upload Document')
                            ->directory('documents')
                            ->disk('public')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->getUploadedFileNameForStorageUsing(
                                function (TemporaryUploadedFile $file) {

                                    $filenameWithoutExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                                    return  Str::slug($filenameWithoutExtension) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                                }
                            )
                            ->required(),
                    ])
                    ->mutateDataUsing(function (array $data) {
                        $data['type'] = 'financial';
                        $data['inventory_item_id'] = $this->inventory_item_id;
                        return $data;
                    })
                    ->hidden(fn () => auth()->user()->isShopManager())
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(fn ($record) => $record->type === 'financial'),
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
