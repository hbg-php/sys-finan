<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContaResource\Pages;
use App\Models\Conta;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContaResource extends Resource
{
    protected static ?string $model = Conta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    private const OPERACIONAL = 1;

    private const PAGO = 1;

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
            ->columns([
                TextColumn::make('fornecedor')->label('Fornecedor')->sortable(),
                TextColumn::make('numeroDocumento')->label('Número do Documento')->sortable(),
                TextColumn::make('valor')->label('Valor')->money('BRL', 0, 'pt_BR')->sortable(),
                TextColumn::make('descricao')->label('Descrição')->sortable(),
                TextColumn::make('tipo')
                    ->getStateUsing(fn (Conta $conta): string => self::OPERACIONAL === $conta->tipo
                        ? 'Operacional'
                        : 'Não Operacional'
                    )
                    ->label('Tipo')
                    ->sortable(),
                TextColumn::make('status')
                    ->getStateUsing(fn (Conta $conta): string => self::PAGO === $conta->tipo
                        ? 'Pago'
                        : 'Não pago'
                    )
                    ->label('Status')
                    ->sortable(),
                TextColumn::make('dataPagamento')
                    ->label('Data de Pagamento')
                    ->sortable()
                    ->date('d-m-Y'),
                TextColumn::make('dataVencimento')
                    ->label('Data de Vencimento')
                    ->sortable()
                    ->date('d-m-Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
