<?php

namespace App\Filament\Resources\Subdomains\Pages;

use App\Filament\Resources\Subdomains\SubdomainResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubdomain extends EditRecord
{
    protected static string $resource = SubdomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
