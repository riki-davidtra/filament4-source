<?php

namespace App\Filament\Auth;

// use Filament\Pages\Auth\Login as FilamentDefaultLoginPage;
use Filament\Auth\Pages\Login as FilamentDefaultLoginPage;

class Login extends FilamentDefaultLoginPage
{
    protected string $view = 'filament.pages.login-custom';
}
