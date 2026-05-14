<?php

namespace App\Filament\Resources\TaxExemptions\Pages;

use App\Filament\Resources\TaxExemptions\TaxExemptionsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaxExemptions extends ListRecords
{
    protected static string $resource = TaxExemptionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
