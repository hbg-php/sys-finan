<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;

final class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-profile';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar')
                    ->label('Foto de perfil')
                    ->avatar()
                    ->image()
                    ->imageEditor()
                    ->directory('avatars')
                    ->visibility('public')
                    ->columnSpanFull(),

                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->operation('edit');
    }
}
