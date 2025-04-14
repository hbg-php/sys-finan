<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;
}
