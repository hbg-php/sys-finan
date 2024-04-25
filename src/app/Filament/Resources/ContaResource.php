<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContaResource\Pages;
use App\Filament\Resources\ContaResource\RelationManagers;
use App\Models\Conta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContaResource extends Resource
{
    protected static ?string $model = Conta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        '0' => 'Operacional',
                        '1' => 'Não Operacional'
                ]),
                Radio::make('status')
                    ->label('Status')
                    ->options([
                        '0' => 'Pago',
                        '1' => 'Não pago'
                ]),
                DatePicker::make('dataPagamento')->label('Data do Pagamento'),
                DatePicker::make('dataVencimento')->label('Data do Vencimento')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
