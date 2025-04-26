<?php

declare(strict_types=1);

namespace App\Filament\Resources\PagamentoResource\Pages;

use App\Action\PagamentoAction;
use App\Filament\Resources\PagamentoResource;
use App\Models\Conta;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

final class CreatePagamento extends CreateRecord
{
    protected static ?string $title = 'Checkout';

    protected static string $resource = PagamentoResource::class;

    private PagamentoAction $pagamentoAction;

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
        /** @var PagamentoAction $pagamentoAction */
        $pagamentoAction = app(PagamentoAction::class);
        $pagamentoAction->handle($data);

        return parent::handleRecordCreation($data);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['data_pagamento'] = now();
        $data['status'] = 1;
        $data['tipo_pagamento'] = 3;
        $data['user_id'] = Auth()->id();

        if (isset($data['conta_id'])) {
            $conta = Conta::find($data['conta_id']);

            if ($conta) {
                $data['valor'] = $conta->valor;
            }
        }
        unset($data['codigoCVV']);
        unset($data['validade']);

        return $data;
    }
}
