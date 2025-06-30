<?php

namespace App\Filament\Pages;

class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-profile';
}
