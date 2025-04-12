<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;

class CustomRegister extends Register
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    protected function handleRegistration(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->getUserModel()::create($data); 
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->autofocus()
                    ->autocomplete()
                    ->extraInputAttributes(['tabindex' => 1]),
                TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->type('string')
                    ->required()
                    ->autocomplete()
                    ->extraInputAttributes(['tabindex' => 2]),
                TextInput::make('razaoSocial')
                    ->label('Razão Social')
                    ->type('string')
                    ->required()
                    ->autocomplete()
                    ->extraInputAttributes(['tabindex' => 3]),
                TextInput::make('email')
                    ->label('Email')
                    ->type('email')
                    ->required()
                    ->autocomplete()
                    ->extraInputAttributes(['tabindex' => 4]),
                TextInput::make('password')
                    ->label('Senha')
                    ->type('password')
                    ->required()
                    ->autocomplete()
                    ->extraInputAttributes(['tabindex' => 5]),
            ])
            ->statePath('data');
    }
}
