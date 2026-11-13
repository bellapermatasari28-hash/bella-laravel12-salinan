<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class CustomerForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Customer Name')
                            ->placeholder('Customer Name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->placeholder('Email Address')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->placeholder('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ]),
            ]);
    }
}