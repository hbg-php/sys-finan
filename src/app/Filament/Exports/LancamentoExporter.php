<?php

namespace App\Filament\Exports;

use App\Models\Lancamento;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LancamentoExporter extends Exporter
{
    protected static ?string $model = Lancamento::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('recebimento'),
            ExportColumn::make('pagamento'),
            ExportColumn::make('tipoRecebimento'),
            ExportColumn::make('tipoPagamento'),
            ExportColumn::make('dataLancamento'),
            ExportColumn::make('user.name'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Sua exportação dos lançamentos está finalizada e tem ' . number_format($export->successful_rows) . ' ' . str('linha')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('linha')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
