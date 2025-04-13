<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
final class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'quantidade_estoque' => $this->faker->numberBetween(0, 100),
            'codigo_barras' => $this->faker->unique()->numerify('##########'),
        ];
    }
}
