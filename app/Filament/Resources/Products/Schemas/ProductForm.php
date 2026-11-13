<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Product Image')
                            ->image()
                            ->required(),

                        TextInput::make('title')
                            ->label('Product Title')
                            ->placeholder('Product Title')
                            ->required(),

                        Grid::make(3)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('weight')
                                    ->label('Weight (gram)')
                                    ->numeric()
                                    ->required(),
                            ]),

                        RichEditor::make('description')
                            ->label('Product Description')
                            ->required(),
                    ]),
            ]);
    }
}
