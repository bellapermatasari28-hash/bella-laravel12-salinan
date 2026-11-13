<?php

namespace App\Filament\Resources\Carts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->required(),

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'title')
                            ->required(),

                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
            ]);
    }
}
