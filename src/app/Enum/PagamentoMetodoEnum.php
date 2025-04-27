<?php

declare(strict_types=1);

namespace App\Enum;

enum PagamentoMetodoEnum: int
{
    case PIX = 1;
    case BOLETO = 2;
    case CARTAO = 3;
}
