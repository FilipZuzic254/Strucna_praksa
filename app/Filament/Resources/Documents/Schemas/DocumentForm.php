<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('file_name')
                    ->label('File Name')
                    ->unique()
                    ->required(),
                FileUpload::make('file_path')
                    ->label('Upload Document')
                    ->directory('documents')
                    ->disk('public')
                    ->getUploadedFileNameForStorageUsing(
                        function (TemporaryUploadedFile $file) {

                            $filenameWithoutExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                            return  Str::slug($filenameWithoutExtension) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                        }
                    )
                    ->required(),
            ]);
    }
}
