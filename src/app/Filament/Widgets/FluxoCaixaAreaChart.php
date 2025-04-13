<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Lancamento;
use Filament\Widgets\ChartWidget;

final class FluxoCaixaAreaChart extends ChartWidget
{
    protected static ?string $heading = 'Fluxo de Caixa';

    protected function getData(): array
    {
        $userId = auth()->id();
        $data = Lancamento::selectRaw('MONTH(created_at) as month, SUM(recebimento) as totalRecebido, SUM(pagamento) as totalPago')
            ->where('user_id', $userId)
            ->groupByRaw('MONTH(created_at)')
            ->get();

        return [
            'labels' => $data->pluck('month')->map(fn ($month) => date('F', mktime(0, 0, 0, $month, 1))),
            'datasets' => [
                [
                    'label' => 'Recebimentos',
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'data' => $data->pluck('totalRecebido'),
                ],
                [
                    'label' => 'Pagamentos',
                    'borderColor' => '#FF6384',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'data' => $data->pluck('totalPago'),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
