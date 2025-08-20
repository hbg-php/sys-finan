<?php

declare(strict_types=1);

namespace App\Filament\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;

final class Login extends BaseAuth
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // $this->getEmailFormComponent(),
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('cnpj')
            ->label('CNPJ')
            ->type('string')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'cnpj' => (string) $data['cnpj'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        Notification::make()
            ->title('Falha no login')
            ->body('CNPJ ou senha incorretos.')
            ->danger()
            ->send();

        throw ValidationException::withMessages([
            'data.cnpj' => 'CNPJ ou senha incorretos.',
        ]);
    }
}
