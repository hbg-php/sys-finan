<?php

namespace App\Filament\Exports;

use App\Models\Conta;
use App\Models\ContasPagas;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ContasPagasExporter extends Exporter
{
    protected static ?string $model = ContasPagas::class;
    private const OPERACIONAL = '1';

    private const PAGO = '1';

    private const NAO_PAGO = '2';

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('fornecedor')
                ->label('Fornecedor'),
            ExportColumn::make('valor')
                ->label('Valor'),
            ExportColumn::make('descricao')
                ->label('Descrição'),
            ExportColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn (Conta $conta): string => self::PAGO === $conta->status
                    ? 'Pago'
                    : 'Não pago'
                ),
            ExportColumn::make('tipo')
                ->label('Tipo')
                ->getStateUsing(fn (Conta $conta): string => self::OPERACIONAL === $conta->tipo
                    ? 'Operacional'
                    : 'Não Operacional'
                ),
            ExportColumn::make('numeroDocumento')->label('Número do Documento'),
            ExportColumn::make('dataVencimento')
                ->label('Data de Vencimento')
                ->getStateUsing(fn (Conta $conta): string => \date('d/m/Y', strtotime($conta->dataVencimento))),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Seu relatório de contas pagas está finalizado e tem ' . number_format($export->successful_rows) . ' ' . str('linha')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('linha')->plural($failedRowsCount) . ' falharam ao serem exportadas.';
        }

        return $body;
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query
            ->where('status', '=', self::PAGO)
            ->orderBy('dataVencimento', 'desc');
    }
}
