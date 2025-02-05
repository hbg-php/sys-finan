<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContaResource\Pages;

use App\Filament\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListContas extends ListRecords
{
    protected static string $resource = ContaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
