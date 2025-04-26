<?php

declare(strict_types=1);

namespace App\Filament\Resources\PagamentoResource\Pages;

use App\Filament\Resources\PagamentoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

final class CreatePagamento extends CreateRecord
{
    protected static ?string $title = 'Checkout';

    protected static string $resource = PagamentoResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Checkout';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->hidden();
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label('Pagar');
    }

    protected function handleRecordCreation(array $data): Model
    {
        dd($data);

        return parent::handleRecordCreation($data);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dataPagamento'] = now();
        $data['status'] = 1;
        $data['tipo_pagamento'] = 3;

        return $data;
    }
}
