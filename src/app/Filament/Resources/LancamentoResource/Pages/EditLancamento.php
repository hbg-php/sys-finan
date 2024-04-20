<?php

namespace App\Filament\Resources\LancamentoResource\Pages;

use App\Filament\Resources\LancamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLancamento extends EditRecord
{
    protected static string $resource = LancamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
