<?php

namespace Database\Factories;

use App\Models\Lancamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lancamento>
 */
class LancamentoFactory extends Factory
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
            'recebimento' => $this->faker->randomFloat(2, 100, 10000), // Valor entre 100 e 10.000
            'pagamento' => $this->faker->randomFloat(2, 100, 10000), // Valor entre 100 e 10.000
            'tipoRecebimento' => $this->faker->randomElement(['1', '2']), // 1 = Dinheiro, 2 = Bancário
            'tipoPagamento' => $this->faker->randomElement(['1', '0']), // 1 = Mercadorias, 0 = Outros
            'dataLancamento' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'), // Data opcional
            'user_id' => 1, // Relaciona com um usuário
        ];
    }
}
