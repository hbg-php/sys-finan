<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateProduto extends CreateRecord
{
    protected static string $resource = ProdutoResource::class;
}
