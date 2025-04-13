<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
final class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->optional()->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
