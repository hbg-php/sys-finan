<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Models\Produto;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $modelLabel = 'Produtos';

    protected static ?string $navigationGroup = 'Estabelecimento';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->label('Nome')
                /* ->maxLength(255) */,
                Textarea::make('descricao')
                    ->label('Descrição')
                    // ->maxLength(500)
                    ->nullable(),
                TextInput::make('preco')
                    ->label('Preço')
                    ->numeric()
                    ->prefix('R$')
                    ->currencyMask('.', ',', 2),
                TextInput::make('quantidade_estoque')
                    ->label('Quantidade em Estoque')
                    ->numeric()
                    ->default(0),
                TextInput::make('codigo_barras')
                    ->label('Código de Barras')
                    ->maxLength(255)
                    ->unique(Produto::class, 'codigo_barras', ignoreRecord: true)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(50) // Limita o texto exibido na tabela
                    ->wrap(), // Quebra o texto se necessário
                TextColumn::make('preco')
                    ->label('Preço')
                    ->money('BRL', true) // Formata como moeda brasileira
                    ->sortable(),
                TextColumn::make('quantidade_estoque')
                    ->label('Quantidade em Estoque')
                    ->sortable(),
                TextColumn::make('codigo_barras')
                    ->label('Código de Barras')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->date('d/m/Y')
                    ->sortable(),
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
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}
