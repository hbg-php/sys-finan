<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
    ];
}
