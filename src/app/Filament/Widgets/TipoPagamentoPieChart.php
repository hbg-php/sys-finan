<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Lancamento;
use Filament\Widgets\ChartWidget;

final class TipoPagamentoPieChart extends ChartWidget
{
    protected static ?string $heading = 'Distribuição de Tipos de Pagamento';

    protected function getData(): array
    {
        $userId = auth()->id();
        $data = Lancamento::selectRaw('tipoPagamento, COUNT(*) as total')
            ->where('user_id', $userId)
            ->groupBy('tipoPagamento')
            ->get();

        return [
            'labels' => $data->pluck('tipoPagamento')->map(fn ($tipo) => $tipo === '1' ? 'Mercadorias' : 'Outros'),
            'datasets' => [
                [
                    'data' => $data->pluck('total'),
                    'backgroundColor' => ['#FFCE56', '#4BC0C0'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
