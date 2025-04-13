<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Lancamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lancamento>
 */
final class LancamentoFactory extends Factory
{
    protected $model = Lancamento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recebimento' => $this->faker->randomFloat(2, 100, 10000),
            'pagamento' => $this->faker->randomFloat(2, 100, 10000),
            'tipoRecebimento' => $this->faker->randomElement(['1', '2']),
            'tipoPagamento' => $this->faker->randomElement(['1', '0']),
            'dataLancamento' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'user_id' => 1,
        ];
    }
}
