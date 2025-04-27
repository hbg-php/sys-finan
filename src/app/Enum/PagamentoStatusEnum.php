<?php

declare(strict_types=1);

namespace App\Enum;

enum PagamentoStatusEnum: int
{
    case APROVADO = 1;
    case REPROVADO = 2;
    case PAGO = 3;
    case CANCELADO = 4;
    case PENDENTE = 5;
}
