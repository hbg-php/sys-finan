<?php

declare(strict_types=1);

namespace App\Filament\Resources\LancamentoResource\Pages;

use App\Filament\Resources\LancamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListLancamentos extends ListRecords
{
    protected static string $resource = LancamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
