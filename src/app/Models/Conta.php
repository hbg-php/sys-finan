<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Conta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fornecedor',
        'valor',
        'descricao',
        'status',
        'tipo',
        'numeroDocumento',
        'dataPagamento',
        'dataVencimento',
        'imagem',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
