<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Cliente;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class ClienteExporter extends Exporter
{
    protected static ?string $model = Cliente::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nome')
                ->label('Nome'),
            ExportColumn::make('cpf')
                ->label('CPF'),
            ExportColumn::make('email')
                ->label('E-mail'),
            ExportColumn::make('telefone')
                ->label('Telefone'),
            ExportColumn::make('created_at')
                ->label('Data de Cadastro')
                ->getStateUsing(fn (Cliente $cliente): string => date('d/m/Y', strtotime($cliente->created_at))),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Seu relatório de clientes foi gerado com sucesso e contém '.number_format($export->successful_rows).' '.str('linha')->plural($export->successful_rows).' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('linha')->plural($failedRowsCount).' falharam ao exportar.';
        }

        return $body;
    }
}
