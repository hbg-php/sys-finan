<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Lancamento;
use Illuminate\Database\Seeder;

final class LancamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lancamento::factory()->count(50)->create();
    }
}
