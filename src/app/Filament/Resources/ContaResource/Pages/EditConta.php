<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContaResource\Pages;

use App\Filament\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditConta extends EditRecord
{
    private const PAGO = '1';

    private const NAO_PAGO = '2';

    protected static string $resource = ContaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            /*Actions\Action::make('pagarConta')
                ->label('Pagar Conta')
                ->color('success')
                ->icon('heroicon-o-currency-dollar')
                ->url(fn () => \App\Filament\Resources\PagamentoResource::getUrl('create', [
                    'conta' => $this->record->getKey(),
                ]))
                ->requiresConfirmation()
                ->hidden(fn () => $this->record->status === self::PAGO),*/
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
