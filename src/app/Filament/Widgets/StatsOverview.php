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
        return [
            Stat::make('Saldo Total', Conta::query()
                ->where('user_id', '=', auth()->id())
                ->sum('valor'))
            ->description('Saldo total de todas as contas cadastradas.')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),

            Stat::make('Total Recebido em Dinheiro', Lancamento::query()
                ->where('user_id', '=', auth()->id())
                ->where('tipoRecebimento', '=', '1')
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->sum('recebimento'))
                ->description('Total de recebimentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),

            Stat::make('Total Recebido em Transação Bancária', Lancamento::query()
                ->where('user_id', '=', auth()->id())
                ->where('tipoRecebimento', '=', '0')
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->sum('recebimento'))
                ->description('Total de recebimentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),

            Stat::make('Total Pago em Mercadorias', Lancamento::query()
                ->where('user_id', '=', auth()->id())
                ->where('tipoPagamento', '=', '1')
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->sum('pagamento'))
                ->description('Total de pagamentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),

            Stat::make('Total Pago em Outros', Lancamento::query()
                ->where('user_id', '=', auth()->id())
                ->where('tipoPagamento', '=', '0')
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->sum('pagamento'))
                ->description('Total de pagamentos do mês.')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),

            Stat::make('Contas Vencendo', Conta::query()
                ->where('user_id', '=', auth()->id())
                ->where('status', '=', '2') // Status de contas não pagas
                ->where('dataVencimento', '>=', now()->startOfMonth())
                ->where('dataVencimento', '<=', now()->endOfMonth())
                ->count())
                ->description('Contas com vencimento no próximo mês.')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Contas não pagas', Conta::query()
                ->where('status', '=', '2')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count()
            )
                ->description('Contas não pagas do mês.'),
            Stat::make('Contas pagas', Conta::query()
                ->where('status', '=', '1')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count()
            )
                ->description('Contas pagas do mês.'),
            Stat::make('Lançamentos', Lancamento::query()->where('user_id', '=', auth()->id())->count())
                ->description('Todos as lançamentos cadastrados no sistema.')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Dinheiro', Lancamento::query()
                ->where('tipoRecebimento', '=', '1')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count()
            )
                ->description('Lançamentos recebidos em dinheiro.'),
            Stat::make('Bancário', Lancamento::query()
                ->where('tipoRecebimento', '=', '0')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count()
            )
                ->description('Lançamentos recebidos através de transação bancária.'),
            Stat::make('Mercadoria', Lancamento::query()
                ->where('tipoPagamento', '=', '1')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count()
            )
                ->description('Mercadorias pagas.'),
            Stat::make('Outros', Lancamento::query()
                ->where('tipoPagamento', '=', '0')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count()
            )
                ->description('Outros pagamentos.'),
        ];
    }
}
