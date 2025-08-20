<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Exports\LancamentoExporter;
use App\Filament\Resources\LancamentoResource\Pages\CreateLancamento;
use App\Filament\Resources\LancamentoResource\Pages\EditLancamento;
use App\Filament\Resources\LancamentoResource\Pages\ListLancamentos;
use App\Models\Lancamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

final class LancamentoResource extends Resource
{
    private const DINHEIRO = '1';

    private const BANCARIO = '0';

    private const MERCADORIAS = '1';

    private const OUTROS = '0';

    protected static ?string $model = Lancamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $modelLabel = 'Lançamentos';

    protected static ?string $navigationGroup = 'Estabelecimento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('recebimento')->label('Recebimento')->currencyMask('.', ',', 2),
                TextInput::make('pagamento')->label('Pagamento')->currencyMask('.', ',', 2),
                Radio::make('tipoRecebimento')->label('Tipo de Recebimento')
                    ->options([
                        '1' => 'Dinheiro',
                        '0' => 'Bancário',
                    ]),
                Radio::make('tipoPagamento')->label('Tipo de Pagamento')
                    ->options([
                        '1' => 'Mercadorias',
                        '0' => 'Outros',
                    ]),
                DatePicker::make('dataLancamento')->label('Data de Lançamento'),
                // FileUpload::make('attachment')->label('Comprovante')->preserveFilenames()->deletable(),
                Hidden::make('user_id'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select(['lancamentos.*'])
            ->where('user_id', auth()->id())
            ->orderBy('dataLancamento', 'desc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(fn (Action $action): Action => $action->icon('heroicon-o-adjustments-vertical'))
            ->columns([
                TextColumn::make('recebimento')
                    ->label('Recebimento')
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable()
                    ->summarize(
                        Sum::make()
                            ->label('Lucro Total')
                            ->using(fn (Collection $records) => $records->sum(fn ($r): int|float => ($r->recebimento ?? 0) - ($r->pagamento ?? 0)))
                    )
                    ->visibleFrom('md'),
                TextColumn::make('pagamento')
                    ->label('Pagamento')
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('tipoRecebimento')
                    ->getStateUsing(fn (Lancamento $lancamento): string => $lancamento->tipoRecebimento === self::DINHEIRO
                        ? 'Dinheiro'
                        : 'Bancário'
                    )
                    ->label('Tipo de Recebimento')
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('tipoPagamento')
                    ->getStateUsing(fn (Lancamento $lancamento): string => $lancamento->tipoPagamento === self::MERCADORIAS
                        ? 'Mercadorias'
                        : 'Outros'
                    )
                    ->label('Tipo de Pagamento')
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('dataLancamento')
                    ->label('Data de Lançamento')
                    ->sortable()
                    ->date('d-m-Y'),
                TextColumn::make('Total')
                    ->getStateUsing(fn (Lancamento $lancamento): float => $lancamento->recebimento - $lancamento->pagamento)
                    ->money('BRL', 0, 'pt_BR')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('Dinheiro')
                    ->query(fn (Builder $builder): Builder => $builder->where('tipoRecebimento', self::DINHEIRO))
                    ->label('Recebimento em Dinheiro'),
                Filter::make('Bancário')
                    ->query(fn (Builder $builder): Builder => $builder->where('tipoRecebimento', self::BANCARIO))
                    ->label('Recebimento Bancário'),
                Filter::make('Mercadoria')
                    ->query(fn (Builder $builder): Builder => $builder->where('tipoPagamento', self::MERCADORIAS))
                    ->label('Pagamento em Mercadorias'),
                Filter::make('Outros')
                    ->query(fn (Builder $builder): Builder => $builder->where('tipoPagamento', self::OUTROS))
                    ->label('Outros'),
                Filter::make('Data de Lançamento')
                    ->form([
                        DatePicker::make('dataLancamentoInicio')->label('Data Inicial'),
                        DatePicker::make('dataLancamentoFim')->label('Data Final'),
                    ])
                    ->query(fn (Builder $builder, array $data): Builder => $builder
                        ->when(
                            $data['dataLancamentoInicio'] ?? null,
                            fn (Builder $builder, $date) => $builder->whereDate('dataLancamento', '>=', $date)
                        )
                        ->when(
                            $data['dataLancamentoFim'] ?? null,
                            fn (Builder $builder, $date) => $builder->whereDate('dataLancamento', '<=', $date)
                        )),
            ])
            ->actions([
                EditAction::make()->hiddenLabel(),
                DeleteAction::make()
                    ->requiresConfirmation(function (Action $action): Action {
                        $action->modalDescription('Tem certeza que deseja excluir este lançamento?');
                        $action->modalHeading('Excluir Lançamento');

                        return $action;
                    })
                    ->hiddenLabel(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('Pdf')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $lancamentos) => response()->streamDownload(function () use ($lancamentos): void {
                            echo Pdf::loadHTML(
                                Blade::render('lancamentos-pdf', ['lancamentos' => $lancamentos])
                            )->stream();
                        }, 'lancamentos.pdf')),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(LancamentoExporter::class)
                    ->label('Relatório de Lançamentos')
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (): string => 'lancamentos'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLancamentos::route('/'),
            'create' => CreateLancamento::route('/create'),
            'edit' => EditLancamento::route('/{record}/edit'),
        ];
    }
}
