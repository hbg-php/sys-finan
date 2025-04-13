<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Lancamento;
use Filament\Widgets\ChartWidget;

final class RecebimentosStackedBarChart extends ChartWidget
{
    protected static ?string $heading = 'Recebimentos por Tipo';

    protected function getData(): array
    {
        $userId = auth()->id();
        $data = Lancamento::selectRaw('MONTH(created_at) as month, tipoRecebimento, SUM(recebimento) as total')
            ->where('user_id', $userId)
            ->groupByRaw('MONTH(created_at), tipoRecebimento')
            ->get();

        $months = $data->pluck('month')->unique()->sort();
        $dinheiro = $months->map(fn ($month) => $data->where('month', $month)->where('tipoRecebimento', '1')->sum('total'));
        $bancario = $months->map(fn ($month) => $data->where('month', $month)->where('tipoRecebimento', '0')->sum('total'));

        return [
            'labels' => $months->map(fn ($month) => date('F', mktime(0, 0, 0, $month, 1))),
            'datasets' => [
                [
                    'label' => 'Dinheiro',
                    'backgroundColor' => '#FF6384',
                    'data' => $dinheiro,
                ],
                [
                    'label' => 'Bancário',
                    'backgroundColor' => '#36A2EB',
                    'data' => $bancario,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
