<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Conta;
use Illuminate\Database\Seeder;

final class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Conta::factory()->count(10)->create();
    }
}
