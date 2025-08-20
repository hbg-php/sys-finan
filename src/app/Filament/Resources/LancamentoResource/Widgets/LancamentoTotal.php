<?php

declare(strict_types=1);

namespace App\Filament\Resources\LancamentoResource\Widgets;

use App\Filament\Resources\LancamentoResource\Pages\ListLancamentos;
use App\Models\Lancamento;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class LancamentoTotal extends BaseWidget
{
    use InteractsWithPageTable;

    protected $listeners = ['refreshLancamentoWidget' => '$refresh'];

    protected function getTablePage(): string
    {
        return ListLancamentos::class;
    }

    protected function getStats(): array
    {
        $userId = auth()->id();

        $recebimentoTotal = Lancamento::where('user_id', $userId)->sum('recebimento');
        $pagamentoTotal = Lancamento::where('user_id', $userId)->sum('pagamento');
        $lucro = $recebimentoTotal - $pagamentoTotal;

        return [
            Stat::make('Total Recebido', 'R$ '.number_format($recebimentoTotal, 2, ',', '.')),
            Stat::make('Total Pago', 'R$ '.number_format($pagamentoTotal, 2, ',', '.')),
            Stat::make('Lucro', 'R$ '.number_format($lucro, 2, ',', '.')),
        ];
    }
}
