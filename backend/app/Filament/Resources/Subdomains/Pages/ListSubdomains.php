<?php

namespace App\Filament\Resources\Subdomains\Pages;

use App\Filament\Resources\Subdomains\SubdomainResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubdomains extends ListRecords
{
    protected static string $resource = SubdomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
