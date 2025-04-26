<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Exports\ContasNaoPagasExporter;
use App\Filament\Exports\ContasPagasExporter;
use App\Filament\Resources\ContaResource\Pages;
use App\Models\Conta;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class ContaResource extends Resource
{
    private const OPERACIONAL = '1';

    private const NAO_OPERACIONAL = '2';

    private const PAGO = '1';

    private const NAO_PAGO = '2';

    protected static ?string $model = Conta::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Contas';

    protected static ?string $navigationGroup = 'Estabelecimento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('fornecedor')->label('Fornecedor'),
                TextInput::make('numeroDocumento')->label('Número do Documento'),
                TextInput::make('valor')->label('Valor')->currencyMask('.', ',', 2),
                TextInput::make('descricao')->label('Descrição'),
                Radio::make('tipo')
                    ->label('Tipo')
                    ->options([
                        self::OPERACIONAL => 'Operacional',
                        self::NAO_OPERACIONAL => 'Não Operacional',
                    ]),
                Radio::make('status')
                    ->label('Status')
                    ->options([
                        self::PAGO => 'Pago',
                        self::NAO_PAGO => 'Não pago',
                    ])
                    ->reactive(),
                DatePicker::make('dataPagamento')->label('Data do Pagamento'),
                DatePicker::make('dataVencimento')->label('Data do Vencimento'),
                FileUpload::make('imagem')
                    ->label('Comprovante')
                    ->image()
                    ->directory('uploads/comprovantes')
                    ->maxSize(1024)
                    ->required(false)
                    ->visible(fn (callable $get) => $get('status') === self::PAGO),
                Hidden::make('user_id'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select(['contas.*'])
            ->where('user_id', auth()->id())
            ->orderBy('dataVencimento', 'desc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filtersTriggerAction(fn (Tables\Actions\Action $action) => $action->icon('heroicon-o-adjustments-vertical')
            )
            ->filtersFormColumns(2)
            ->columns([
                TextColumn::make('fornecedor')
                    ->label('Fornecedor')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('valor')
                    ->label('Valor'),
                TextColumn::make('tipo')
                    ->getStateUsing(fn (Conta $conta): string => $conta->tipo === self::OPERACIONAL
                        ? 'Operacional'
                        : 'Não Operacional'
                    )
                    ->label('Tipo')
                    ->searchable()
                    ->visibleFrom('md'),
                ToggleColumn::make('status')
                    ->beforeStateUpdated(fn (Conta $conta) => $conta->status !== self::PAGO
                        ? $conta->update(['dataPagamento' => date('Y-m-d')])
                        : $conta->update(['dataPagamento' => null])
                    )
                    ->getStateUsing(fn (Conta $conta): bool => $conta->status === self::PAGO)
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Status'),
                TextColumn::make('dataPagamento')
                    ->label('Data de Pagamento')
                    ->date('d-m-Y')
                    ->visibleFrom('md'),
                TextColumn::make('dataVencimento')
                    ->label('Data de Vencimento')
                    ->date('d-m-Y'),
            ])
            ->filters([
                Filter::make('fornecedor')
                    ->form([
                        TextInput::make('fornecedor')->label('Fornecedor'),
                    ])
                    ->query(function (Builder $query, $data) {
                        return $query
                            ->when(
                                $data['fornecedor'],
                                fn (Builder $query, $value): Builder => $query->where('fornecedor', 'LIKE', "%{$data['fornecedor']}%")
                            );
                    }),
                Filter::make('Valor')
                    ->form([
                        TextInput::make('valor')
                            ->label('Valor')
                            ->numeric()
                            ->currencyMask('.', ',', 2),
                    ])
                    ->query(function (Builder $query, $data): Builder {
                        if (! empty($data['valor'])) {
                            $valorFloat = (float) str_replace(',', '.', $data['valor']);

                            return $query
                                ->when(
                                    $valorFloat > 0,
                                    fn (Builder $query, $valor): Builder => $query
                                        ->where(
                                            'valor',
                                            '=',
                                            number_format($valorFloat * 10, 2, '.', '')
                                        )
                                );
                        }

                        return $query;
                    }),
                Filter::make('Contas Pagas')
                    ->query(fn (Builder $query): Builder => $query->where('status', self::PAGO)),
                Filter::make('Contas Não Pagas')
                    ->query(fn (Builder $query): Builder => $query->where('status', self::NAO_PAGO)),
                Filter::make('Operacional')
                    ->query(fn (Builder $query): Builder => $query->where('tipo', self::OPERACIONAL)),
                Filter::make('Não Operacional')
                    ->query(fn (Builder $query): Builder => $query->where('tipo', self::NAO_OPERACIONAL)),
                Filter::make('dataIntervalo')
                    ->form([
                        DatePicker::make('dataVencimento')->label('Data inicial:'),
                    ])
                    ->query(function (Builder $query, $data) {
                        return $query
                            ->when(
                                $data['dataVencimento'],
                                fn (Builder $query, $date): Builder => $query->whereDate('dataVencimento', '>=', $date)
                            );
                    }),
                Filter::make('dataIntervalo2')
                    ->form([
                        DatePicker::make('dataVencimento')->label('Data Final:'),
                    ])
                    ->query(function (Builder $query, $data) {
                        return $query
                            ->when(
                                $data['dataVencimento'],
                                fn (Builder $query, $date): Builder => $query->whereDate('dataVencimento', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(function (Tables\Actions\Action $action) {
                        $action->modalDescription('Tem certeza que deseja excluir esta conta?');
                        $action->modalHeading('Excluir Conta');

                        return $action;
                    })
                    ->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])/* ->headerActions([
                ExportAction::make('relatorioContasNaoPagas')
                    ->exporter(ContasNaoPagasExporter::class)
                    ->label('Relatório Contas Não Pagas')
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (): string => 'contas_nao_pagas'),
                ExportAction::make('relatorioContasPagas')
                    ->exporter(ContasPagasExporter::class)
                    ->label('Relatório Contas Pagas')
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (): string => 'contas_pagas'),
            ]) */;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContas::route('/'),
            'create' => Pages\CreateConta::route('/create'),
            'edit' => Pages\EditConta::route('/{record}/edit'),
        ];
    }
}
