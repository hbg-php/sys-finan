<?php

declare(strict_types=1);

namespace App\Filament\Resources\PagamentoResource\Pages;

use App\Filament\Resources\PagamentoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dataPagamento'] = now();
        $data['status'] = '1';

        return $data;
    }
}
