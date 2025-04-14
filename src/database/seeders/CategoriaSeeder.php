<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

final class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nome' => 'Alimentos',
                'descricao' => 'Produtos alimentícios em geral.',
                'ativo' => true,
            ],
            [
                'nome' => 'Bebidas',
                'descricao' => 'Refrigerantes, sucos, águas, etc.',
                'ativo' => true,
            ],
            [
                'nome' => 'Limpeza',
                'descricao' => 'Produtos de limpeza e higiene do lar.',
                'ativo' => true,
            ],
            [
                'nome' => 'Higiene Pessoal',
                'descricao' => 'Sabonetes, shampoos, cremes, etc.',
                'ativo' => true,
            ],
            [
                'nome' => 'Utilidades Domésticas',
                'descricao' => 'Itens para casa e cozinha.',
                'ativo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
