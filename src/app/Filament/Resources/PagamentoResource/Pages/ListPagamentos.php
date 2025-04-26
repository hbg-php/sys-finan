<?php

declare(strict_types=1);

namespace App\Filament\Resources\PagamentoResource\Pages;

use App\Filament\Resources\PagamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListPagamentos extends ListRecords
{
    protected static string $resource = PagamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
