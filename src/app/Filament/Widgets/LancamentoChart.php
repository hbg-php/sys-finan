<?php

namespace App\Filament\Widgets;

use App\Models\Lancamento;
use Filament\Widgets\ChartWidget;

class LancamentoChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?string $modelLabel = 'Gráficos Lançamentos';

    protected function getData(): array
    {
        $data = [
            'dinheiro' => Lancamento::query()
                ->where('tipoRecebimento', '=', '1')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count(),

            'bancario' => Lancamento::query()
                ->where('tipoRecebimento', '=', '0')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count(),

            'mercadoria' => Lancamento::query()
                ->where('tipoPagamento', '=', '1')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count(),

            'outros' => Lancamento::query()
                ->where('tipoPagamento', '=', '0')
                ->where('user_id', '=', auth()->id())
                ->where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->count(),
        ];

        $labels = ['Dinheiro', 'Bancário', 'Mercadoria', 'Outros'];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                    ],
                    'hoverBackgroundColor' => [
                        '#FF4372',
                        '#34A1D1',
                        '#FFB94D',
                        '#4BBFBF',
                    ]
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
