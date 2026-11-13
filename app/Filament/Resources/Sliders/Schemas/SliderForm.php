<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class SliderForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Slider Image')
                            ->required(),
                        Forms\Components\TextInput::make('link')
                            ->label('Link')
                            ->placeholder('https://...')
                            ->required(),
                    ]),
            ]);
    }
}