<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

final class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produto::factory()->count(200)->create();
    }
}
