<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LancamentoResource\Pages;
use App\Filament\Resources\LancamentoResource\RelationManagers;
use App\Models\Lancamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LancamentoResource extends Resource
{
    protected static ?string $model = Lancamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    private const DINHEIRO = 0;
    private const MERCADORIAS = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Money::make('recebimento')->label('Recebimento'),
                Money::make('pagamento')->label('Pagamento'),
                Radio::make('tipoRecebimento')->label('Tipo de Recebimento')
                    ->options([
                        '0' => 'Dinheiro',
                        '1' => 'Bancário'
                ]),
                Radio::make('tipoPagamento')->label('Tipo de Pagamento')
                    ->options([
                        '0' => 'Mercadorias',
                        '1' => 'Outros'
                ]),
                DatePicker::make('dataLancamento')->label('Data de Lançamento'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('recebimento')->label('Recebimento')->sortable(),
                TextColumn::make('pagamento')->label('Pagamento')->sortable(),
                TextColumn::make('tipoRecebimento')
                    ->getStateUsing(fn (Lancamento $lancamento): string => self::DINHEIRO === $lancamento->tipoRecebimento ? 'Dinheiro' : 'Bancário')
                    ->label('Tipo de Recebimento')
                    ->sortable(),
                TextColumn::make('tipoPagamento')
                    ->getStateUsing(fn (Lancamento $lancamento): string => self::MERCADORIAS === $lancamento->tipoPagamento ? 'Mercadorias' : 'Outros')
                    ->label('Tipo de Pagamento')
                    ->sortable(),
                TextColumn::make('dataLancamento')->label('Data de Lançamento')->sortable()->date('d-m-Y'),
                TextColumn::make('Total')
                    ->getStateUsing(fn (Lancamento $lancamento): string => $lancamento->recebimento - $lancamento->pagamento)
                    ->sortable()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLancamentos::route('/'),
            'create' => Pages\CreateLancamento::route('/create'),
            'edit' => Pages\EditLancamento::route('/{record}/edit'),
        ];
    }
}
