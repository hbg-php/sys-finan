<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContaResource\Pages;

use App\Filament\Resources\ContaResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateConta extends CreateRecord
{
    protected static string $resource = ContaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
