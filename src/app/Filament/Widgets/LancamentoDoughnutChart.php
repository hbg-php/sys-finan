<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Lancamento;
use Filament\Widgets\ChartWidget;

final class LancamentoDoughnutChart extends ChartWidget
{
    protected static ?string $heading = 'Gráficos Lançamentos';

    protected static ?string $modelLabel = 'Gráficos Lançamentos';

    protected function getData(): array
    {
        $userId = auth()->id();
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $types = [
            'dinheiro' => ['tipoRecebimento', '1', '#FF6384', '#FF4372'],
            'bancario' => ['tipoRecebimento', '0', '#36A2EB', '#34A1D1'],
            'mercadoria' => ['tipoPagamento', '1', '#FFCE56', '#FFB94D'],
            'outros' => ['tipoPagamento', '0', '#4BC0C0', '#4BBFBF'],
        ];

        $data = [];
        $backgroundColors = [];
        $hoverBackgroundColors = [];
        $labels = [];

        foreach ($types as $label => [$column, $value, $bgColor, $hoverColor]) {
            $data[] = Lancamento::query()
                ->where($column, '=', $value)
                ->where('user_id', '=', $userId)
                ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->count();

            $labels[] = ucfirst($label);
            $backgroundColors[] = $bgColor;
            $hoverBackgroundColors[] = $hoverColor;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'hoverBackgroundColor' => $hoverBackgroundColors,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
