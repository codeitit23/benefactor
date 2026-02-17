<?php

namespace App\Filament\Pages;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;
use Filament\Forms;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->placeholder('مثال: 70123456')
                    ->required()
                    ->minLength(8)
                    ->maxLength(8)
                    ->rule('regex:/^\\d{8}$/'),
                Forms\Components\Textarea::make('address')
                    ->label('العنوان')
                    ->rows(3)
                    ->maxLength(500),
            ]);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['role'] = 'user';

        return $data;
    }
}
