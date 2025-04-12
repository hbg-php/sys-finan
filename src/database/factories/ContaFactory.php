<?php

namespace Database\Factories;

use App\Models\Conta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conta>
 */
class ContaFactory extends Factory
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
            'fornecedor' => $this->faker->company(), // Nome do fornecedor
            'valor' => $this->faker->randomFloat(2, 100, 10000), // Valor entre 100 e 10.000
            'descricao' => $this->faker->sentence(), // Descrição opcional
            'status' => $this->faker->randomElement(['1', '2']), // 1 = Pago, 2 = Não pago
            'tipo' => $this->faker->randomElement(['1', '2']), // 1 = Operacional, 2 = Não Operacional
            'numeroDocumento' => $this->faker->unique()->numerify('DOC-#####'), // Documento único
            'dataPagamento' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'), // Data de pagamento opcional
            'dataVencimento' => $this->faker->dateTimeBetween('now', '+1 month'), // Data de vencimento futura
            'user_id' => 1, // Relaciona com um usuário
        ];
    }
}
