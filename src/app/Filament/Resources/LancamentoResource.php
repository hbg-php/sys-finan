<?php

namespace App\Filament\Resources;

use App\Filament\Exports\LancamentoExporter;
use App\Filament\Resources\LancamentoResource\Pages;
use App\Models\Lancamento;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Form;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LancamentoResource extends Resource
{
    protected static ?string $model = Lancamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $modelLabel = 'Lançamentos';

    protected static ?string $navigationGroup = 'Estabelecimento';

    private const DINHEIRO = '1';

    private const BANCARIO = '2';

    private const MERCADORIAS = '1';

    private const OUTROS = '0';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Money::make('recebimento')->label('Recebimento'),
                Money::make('pagamento')->label('Pagamento'),
                Radio::make('tipoRecebimento')->label('Tipo de Recebimento')
                    ->options([
                        '1' => 'Dinheiro',
                        '0' => 'Bancário'
                ]),
                Radio::make('tipoPagamento')->label('Tipo de Pagamento')
                    ->options([
                        '1' => 'Mercadorias',
                        '0' => 'Outros'
                ]),
                DatePicker::make('dataLancamento')->label('Data de Lançamento'),
                Hidden::make('user_id')
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->select(['lancamentos.*'])
            ->where('user_id', auth()->id())
            ->orderBy('dataLancamento', 'desc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('recebimento')
                    ->label('Recebimento')
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable(),
                TextColumn::make('pagamento')
                    ->label('Pagamento')
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable(),
                TextColumn::make('tipoRecebimento')
                    ->getStateUsing(fn (Lancamento $lancamento): string => self::DINHEIRO === $lancamento->tipoRecebimento
                        ? 'Dinheiro'
                        : 'Bancário'
                    )
                    ->label('Tipo de Recebimento')
                    ->sortable(),
                TextColumn::make('tipoPagamento')
                    ->getStateUsing(fn (Lancamento $lancamento): string => self::MERCADORIAS === $lancamento->tipoPagamento
                        ? 'Mercadorias'
                        : 'Outros'
                    )
                    ->label('Tipo de Pagamento')
                    ->sortable(),
                TextColumn::make('dataLancamento')->label('Data de Lançamento')->sortable()->date('d-m-Y'),
                TextColumn::make('Total')
                    ->getStateUsing(fn (Lancamento $lancamento): string => $lancamento->recebimento - $lancamento->pagamento)
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable()
            ])
            ->filters([
                Filter::make('Dinheiro')
                    ->query(fn (Builder $query): Builder => $query->where('tipoRecebimento', self::DINHEIRO)),
                Filter::make('Bancário')
                    ->query(fn (Builder $query): Builder => $query->where('tipoRecebimento', self::BANCARIO)),
                Filter::make('Mercadoria')
                    ->query(fn (Builder $query): Builder => $query->where('tipoPagamento', self::MERCADORIAS)),
                Filter::make('Outros')
                    ->query(fn (Builder $query): Builder => $query->where('tipoPagamento', self::OUTROS)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(LancamentoExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLancamentos::route('/'),
            'create' => Pages\CreateLancamento::route('/create'),
            'edit' => Pages\EditLancamento::route('/{record}/edit'),
        ];
    }
}
