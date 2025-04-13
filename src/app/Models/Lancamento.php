<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Lancamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'recebimento',
        'pagamento',
        'tipoRecebimento',
        'tipoPagamento',
        'dataLancamento',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
