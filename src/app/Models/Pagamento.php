<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Pagamento extends Model
{
    protected $fillable = [
        'user_id',
        'conta_id',
        'nome_titular_cartao',
        'numero_cartao',
        'data_pagamento',
        'tipo_pagamento',
        'status',
        'valor',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
