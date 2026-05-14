<?php

namespace App\Filament\Resources\TaxExemptions\Pages;

use App\Filament\Resources\TaxExemptions\TaxExemptionsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTaxExemptions extends ViewRecord
{
    protected static string $resource = TaxExemptionsResource::class;

    protected static ?string $modelLabel  = 'name';

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
