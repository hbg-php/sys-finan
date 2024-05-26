<?php

namespace App\Filament\Exports;

use App\Models\Lancamento;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LancamentoExporter extends Exporter
{
    protected static ?string $model = Lancamento::class;

    private const DINHEIRO = '1';

    private const BANCARIO = '2';

    private const MERCADORIAS = '1';

    private const OUTROS = '0';

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('recebimento')
                ->label('Recebimento'),
            ExportColumn::make('pagamento')
                ->label('Pagamento'),
            ExportColumn::make('tipoRecebimento')
                ->label('Tipo de Recebimento')
                ->getStateUsing(fn (Lancamento $lancamento): string => self::DINHEIRO === $lancamento->tipoRecebimento
                    ? 'Dinheiro'
                    : 'Bancário'
                ),
            ExportColumn::make('tipoPagamento')
                ->label('Tipo de Pagamento')
                ->getStateUsing(fn (Lancamento $lancamento): string => self::MERCADORIAS === $lancamento->tipoPagamento
                    ? 'Mercadorias'
                    : 'Outros'
                ),
            ExportColumn::make('dataLancamento')
                ->label('Data do Lançamento')
                ->getStateUsing(fn (Lancamento $lancamento): string => \date('d/m/Y', strtotime($lancamento->dataLancamento))),
            ExportColumn::make('created_at')
                ->label('Data do Cadastro')
                ->getStateUsing(fn (Lancamento $lancamento): string => \date('d/m/Y', strtotime($lancamento->created_at))),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Seu relatório de todos os lançamentos está finalizado e tem ' . number_format($export->successful_rows) . ' ' . str('linha')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('linha')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
