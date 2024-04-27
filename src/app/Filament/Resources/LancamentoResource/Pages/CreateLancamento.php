<?php

namespace App\Filament\Resources\LancamentoResource\Pages;

use App\Filament\Resources\LancamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLancamento extends CreateRecord
{
    protected static string $resource = LancamentoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
