<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Conta;
use App\Models\Lancamento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $saldoTotal = Conta::where('user_id', $userId)->sum('valor');
        $totalRecebidoDinheiro = Lancamento::where('user_id', $userId)
            ->where('tipoRecebimento', '1')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('recebimento');
        $totalRecebidoBancario = Lancamento::where('user_id', $userId)
            ->where('tipoRecebimento', '0')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('recebimento');
        $totalPagoMercadorias = Lancamento::where('user_id', $userId)
            ->where('tipoPagamento', '1')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('pagamento');
        $totalPagoOutros = Lancamento::where('user_id', $userId)
            ->where('tipoPagamento', '0')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('pagamento');
        $contasVencendo = Conta::where('user_id', $userId)
            ->where('status', '2')
            ->whereBetween('dataVencimento', [$currentMonthStart, $currentMonthEnd])
            ->count();
        $contasNaoPagas = Conta::where('user_id', $userId)
            ->where('status', '2')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        $contasPagas = Conta::where('user_id', $userId)
            ->where('status', '1')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        $totalLancamentos = Lancamento::where('user_id', $userId)->count();

        $saldoTotalFormatado = 'R$ '.number_format($saldoTotal, 2, ',', '.');
        $totalRecebidoDinheiroFormatado = 'R$ '.number_format($totalRecebidoDinheiro, 2, ',', '.');
        $totalRecebidoBancarioFormatado = 'R$ '.number_format($totalRecebidoBancario, 2, ',', '.');
        $totalPagoMercadoriasFormatado = 'R$ '.number_format($totalPagoMercadorias, 2, ',', '.');
        $totalPagoOutrosFormatado = 'R$ '.number_format($totalPagoOutros, 2, ',', '.');

        return [
            Stat::make('Saldo Total', $saldoTotalFormatado)
                ->description('Saldo total de todas as contas cadastradas.')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),

            Stat::make('Total Recebido em Dinheiro', $totalRecebidoDinheiroFormatado)
                ->description('Total de recebimentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),

            Stat::make('Total Recebido em Transação Bancária', $totalRecebidoBancarioFormatado)
                ->description('Total de recebimentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),

            Stat::make('Total Pago em Mercadorias', $totalPagoMercadoriasFormatado)
                ->description('Total de pagamentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),

            Stat::make('Total Pago em Outros', $totalPagoOutrosFormatado)
                ->description('Total de pagamentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),

            Stat::make('Contas Vencendo', $contasVencendo)
                ->description('Contas com vencimento no próximo mês.')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Contas Não Pagas', $contasNaoPagas)
                ->description('Contas não pagas do mês.')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger'),

            Stat::make('Contas Pagas', $contasPagas)
                ->description('Contas pagas do mês.')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Total de Lançamentos', $totalLancamentos)
                ->description('Todos os lançamentos cadastrados no sistema.')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
