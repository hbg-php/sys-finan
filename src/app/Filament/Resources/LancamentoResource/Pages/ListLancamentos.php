<?php

declare(strict_types=1);

namespace App\Filament\Resources\LancamentoResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LancamentoResource;
use App\Filament\Resources\LancamentoResource\Widgets\LancamentoTotal;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

final class ListLancamentos extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = LancamentoResource::class;

    public function updated($name): void
    {
        if (str_contains((string) $name, 'tableFilters')) {
            $this->emit('refreshLancamentoWidget');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LancamentoTotal::class,
        ];
    }
}
