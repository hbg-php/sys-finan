<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ContasNaoPagasExporter;
use App\Filament\Resources\ContaResource\Pages;
use App\Models\Conta;
use Filament\Tables\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContaResource extends Resource
{
    protected static ?string $model = Conta::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Contas';

    protected static ?string $navigationGroup = 'Estabelecimento';

    private const OPERACIONAL = '1';

    private const NAO_OPERACIONAL = '2';

    private const PAGO = '1';

    private const NAO_PAGO = '2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('fornecedor')->label('Fornecedor'),
                TextInput::make('numeroDocumento')->label('Número do Documento'),
                Money::make('valor')->label('Valor'),
                TextInput::make('descricao')->label('Descrição'),
                Radio::make('tipo')
                    ->label('Tipo')
                    ->options([
                        '1' => 'Operacional',
                        '2' => 'Não Operacional'
                ]),
                Radio::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Pago',
                        '2' => 'Não pago'
                ]),
                DatePicker::make('dataPagamento')->label('Data do Pagamento'),
                DatePicker::make('dataVencimento')->label('Data do Vencimento'),
                Hidden::make('user_id')
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->select(['contas.*'])
            ->where('user_id', auth()->id())
            ->orderBy('dataVencimento', 'desc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(fn (Tables\Actions\Action $action) =>
                $action->icon('heroicon-o-adjustments-vertical')
            )
            ->columns([
                TextColumn::make('fornecedor')
                    ->label('Fornecedor')
                    ->sortable()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('numeroDocumento')
                    ->label('Número do Documento')
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable(),
                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('tipo')
                    ->getStateUsing(fn (Conta $conta): string => self::OPERACIONAL === $conta->tipo
                        ? 'Operacional'
                        : 'Não Operacional'
                    )
                    ->label('Tipo')
                    ->sortable()
                    ->searchable()
                    ->visibleFrom('md'),
                ToggleColumn::make('status')
                    ->beforeStateUpdated(fn (Conta $conta) => self::PAGO !== $conta->status
                        ? $conta->update(['dataPagamento' => date('Y-m-d')])
                        : $conta->update(['dataPagamento' => null])
                    )
                    ->getStateUsing(fn (Conta $conta): string => self::OPERACIONAL === $conta->status)
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Status')
                    ->sortable(),
                TextColumn::make('dataPagamento')
                    ->label('Data de Pagamento')
                    ->sortable()
                    ->date('d-m-Y')
                    ->visibleFrom('md'),
                TextColumn::make('dataVencimento')
                    ->label('Data de Vencimento')
                    ->sortable()
                    ->date('d-m-Y'),
            ])
            ->filters([
                Filter::make('Contas Pagas')
                    ->query(fn (Builder $query): Builder => $query->where('status', self::PAGO)),
                Filter::make('Contas Não Pagas')
                    ->query(fn (Builder $query): Builder => $query->where('status', self::NAO_PAGO)),
                Filter::make('Operacional')
                    ->query(fn (Builder $query): Builder => $query->where('tipo', self::OPERACIONAL)),
                Filter::make('Não Operacional')
                    ->query(fn (Builder $query): Builder => $query->where('tipo', self::NAO_OPERACIONAL)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                ExportAction::make()
                    ->exporter(ContasNaoPagasExporter::class)
                    ->label('Relatório Contas Não Pagas')
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (): string => 'contas_nao_pagas'),
                /*ExportAction::make()
                    ->exporter(ContasPagasExporter::class)
                    ->label('Relatório Contas Pagas')
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (): string => 'contas_pagas'),*/
            ]);
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
