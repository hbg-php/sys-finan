<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'ativo',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
