<?php

declare(strict_types=1);

namespace App\Action;

use App\Models\Pagamento;
use Exception;
use Illuminate\Support\Arr;
use Stripe\PaymentIntent;
use Stripe\Stripe;

final class PagamentoAction
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function handle(array $data)
    {
        if ((float) $data['valor'] <= 0) {
            throw new Exception('Pagamento recusado: valor inválido.');
        }

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($data['valor'] * 100),
                'currency' => 'BRL',
                'payment_method_types' => ['card'],
                'payment_method' => $data['payment_method_id'],
                'confirmation_method' => 'automatic',
                'confirm' => true,
            ]);

            if ($paymentIntent->status === 'succeeded') {
                $fillable = (new Pagamento)->getFillable();

                $filteredData = Arr::only($data, $fillable);

                return Pagamento::create($filteredData);
            }
            throw new Exception('Pagamento não foi concluído. Status: '.$paymentIntent->status);

        } catch (\Stripe\Exception\CardException $e) {
            throw new Exception('Erro no cartão: '.$e->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new Exception('Erro de API do Stripe: '.$e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Erro ao processar pagamento: '.$e->getMessage());
        }
    }
}
