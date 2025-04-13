<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Lancamento;
use Filament\Widgets\ChartWidget;

final class SaldoLineChart extends ChartWidget
{
    protected static ?string $heading = 'Evolução do Saldo';

    protected function getData(): array
    {
        $userId = auth()->id();
        $data = Lancamento::selectRaw('MONTH(created_at) as month, SUM(recebimento - pagamento) as saldo')
            ->where('user_id', $userId)
            ->groupByRaw('MONTH(created_at)')
            ->get();

        return [
            'labels' => $data->pluck('month')->map(fn ($month) => date('F', mktime(0, 0, 0, $month, 1))),
            'datasets' => [
                [
                    'label' => 'Saldo',
                    'borderColor' => '#4BC0C0',
                    'data' => $data->pluck('saldo'),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
