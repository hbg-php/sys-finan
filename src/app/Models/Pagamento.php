<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Pagamento extends Model
{
    protected $fillable = [
        'cliente_id',
        'conta_id',
        'numCartao',
        'codigoCVV',
        'validade',
        'dataPagamento',
        'status',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
