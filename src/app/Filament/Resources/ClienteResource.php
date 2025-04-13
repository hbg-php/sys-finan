<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $modelLabel = 'Clientes';

    protected static ?string $navigationGroup = 'Estabelecimento';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('cpf')
                    ->label('CPF')
                    ->required()
                    ->maxLength(14)
                    ->unique(Cliente::class, 'cpf', ignoreRecord: true),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(Cliente::class, 'email', ignoreRecord: true),
                TextInput::make('telefone')
                    ->label('Telefone')
                    ->tel()
                    ->maxLength(15)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(fn (Tables\Actions\Action $action) => $action->icon('heroicon-o-adjustments-vertical'))
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefone')
                    ->label('Telefone')
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
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make('exportarClientes')
                    ->exporter(\App\Filament\Exports\ClienteExporter::class)
                    ->label('Exportar Clientes')
                    ->formats([
                        \Filament\Actions\Exports\Enums\ExportFormat::Csv,
                        \Filament\Actions\Exports\Enums\ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (): string => 'clientes_exportados'),
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
