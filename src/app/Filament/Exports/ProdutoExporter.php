<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Produto;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class ProdutoExporter extends Exporter
{
    protected static ?string $model = Produto::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nome')
                ->label('Nome'),
            ExportColumn::make('descricao')
                ->label('Descrição'),
            ExportColumn::make('preco')
                ->label('Preço')
                ->getStateUsing(fn (Produto $produto): string => 'R$ '.number_format((float) $produto->preco, 2, ',', '.')),
            ExportColumn::make('quantidade_estoque')
                ->label('Quantidade em Estoque')
                ->getStateUsing(fn (Produto $produto): string => number_format($produto->quantidade_estoque)),
            ExportColumn::make('codigo_barras')
                ->label('Código de Barras')
                ->getStateUsing(fn (Produto $produto): string => $produto->codigo_barras ?? 'N/A'),
            ExportColumn::make('created_at')
                ->label('Data de Cadastro')
                ->getStateUsing(fn (Produto $produto): string => $produto->created_at->format('d/m/Y')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Seu relatório de produtos foi gerado com sucesso e contém '.number_format($export->successful_rows).' '.str('linha')->plural($export->successful_rows).' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('linha')->plural($failedRowsCount).' falharam ao exportar.';
        }

        return $body;
    }
}
