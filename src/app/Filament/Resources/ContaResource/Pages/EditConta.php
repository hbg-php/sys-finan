<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContaResource\Pages;

use App\Filament\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditConta extends EditRecord
{
    protected static string $resource = ContaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
