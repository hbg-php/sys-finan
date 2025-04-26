<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PagamentoResource\Pages;
use App\Models\Conta;
use App\Models\Pagamento;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class PagamentoResource extends Resource
{
    protected static ?string $model = Pagamento::class;

    protected static ?string $navigationGroup = 'Pagamentos';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('conta_id')
                    ->default(fn () => request()->input('conta')),
                Forms\Components\Placeholder::make('valorConta')
                    ->label('Valor da Conta')
                    ->content(function () {
                        $contaId = request()->input('conta');
                        if (! $contaId) {
                            return 'Conta não encontrada';
                        }
                        $conta = Conta::find($contaId);

                        return $conta ? 'R$ '.number_format((float) $conta->valor, 2, ',', '.') : 'Conta não encontrada';
                    })->columnSpan('full'),

                TextInput::make('numero_cartao')
                    ->label('Número do Cartão')
                    ->required()
                    ->rule(['digits:16'])
                    ->placeholder('Digite o número do cartão.'),

                TextInput::make('nome_titular_cartao')
                    ->label('Nome do Titular')
                    ->required()
                    ->placeholder('Digite o nome do titular.'),

                TextInput::make('codigoCVV')
                    ->label('Código de Segurança (CVV)')
                    ->required()
                    ->rule('digits:3')
                    ->placeholder('Digite o código de segurança.'),

                TextInput::make('validade')
                    ->label('Validade')
                    ->required()
                    ->mask('99/99')
                    ->placeholder('MM/AA'),
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
            'index' => Pages\ListPagamentos::route('/'),
            'create' => Pages\CreatePagamento::route('/create'),
        ];
    }
}
