<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

final class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::factory()->count(20)->create();
    }
}
