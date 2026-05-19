<?php

namespace App\Filament\Resources\KpdCodes\Pages;

use App\Filament\Resources\KpdCodes\KpdCodesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKpdCodes extends ViewRecord
{
    protected static string $resource = KpdCodesResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
