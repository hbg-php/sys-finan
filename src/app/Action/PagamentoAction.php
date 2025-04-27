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
        $this->setStripeApiKey();
    }

    public function handle(array $data)
    {
        $this->validatePaymentData($data);

        $paymentIntent = $this->createPaymentIntent($data);

        if ($paymentIntent->status !== 'succeeded') {
            throw new Exception(
                'Pagamento recusado: '.$paymentIntent->last_payment_error->message
            );
        }

        return $this->storePayment($data);
    }

    private function validatePaymentData(array $data): void
    {
        if (empty($data['payment_method_id'])) {
            throw new Exception('ID do método de pagamento é obrigatório.');
        }

        if ((float) $data['valor'] <= 0) {
            throw new Exception('Pagamento recusado: valor inválido.');
        }
    }

    private function createPaymentIntent(array $data): PaymentIntent
    {
        try {
            return PaymentIntent::create([
                'amount' => (int) ($data['valor'] * 100),
                'currency' => 'BRL',
                'payment_method_types' => ['card'],
                'payment_method' => $data['payment_method_id'],
                'confirmation_method' => 'automatic',
                'confirm' => true,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            throw new Exception('Erro no cartão: '.$e->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new Exception('Erro de API do Stripe: '.$e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Erro ao processar pagamento: '.$e->getMessage());
        }
    }

    private function storePayment(array $data): Pagamento
    {
        $fillable = (new Pagamento)->getFillable();
        $filteredData = Arr::only($data, $fillable);

        return Pagamento::create($filteredData);
    }

    private function setStripeApiKey(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
}
