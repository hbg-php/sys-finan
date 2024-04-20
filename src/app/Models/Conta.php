<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conta extends Model
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
        'dataVencimento'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
