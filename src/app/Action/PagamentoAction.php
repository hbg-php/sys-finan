<?php

declare(strict_types=1);

namespace App\Action;

use App\Models\Pagamento;
use Exception;
use Illuminate\Support\Arr;

final class PagamentoAction
{
    public function __construct() {}

    public function handle(array $data): void
    {
        if ((float) $data['valor'] <= 0) {
            throw new Exception('Pagamento recusado: valor inválido.');
        }

        $fillable = (new Pagamento)->getFillable();

        // Filtra apenas os campos permitidos
        $filteredData = Arr::only($data, $fillable);

        Pagamento::create($data);
    }
}
