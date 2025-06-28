<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Conta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conta>
 */
final class ContaFactory extends Factory
{
    protected $model = Conta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fornecedor' => $this->faker->company(),
            'valor' => $this->faker->randomFloat(2, 100, 10000),
            'descricao' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['1', '2']),
            'tipo' => $this->faker->randomElement(['1', '2']),
            'numero_documento' => $this->faker->unique()->numerify('DOC-#####'),
            'data_pagamento' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'data_Vencimento' => $this->faker->dateTimeBetween('now', '+1 month'),
            'user_id' => 1,
        ];
    }
}
