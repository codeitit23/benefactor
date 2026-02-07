<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function throttleKey(): string
    {
        return '';
    }

    protected function ensureIsNotRateLimited(): void
    {
        // Do nothing to disable rate limiting
    }
}