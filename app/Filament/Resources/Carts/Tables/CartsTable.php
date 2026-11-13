<?php

namespace App\Filament\Resources\Carts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class CartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product Title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total Price')
                    ->money('IDR', locale: 'id')
                    ->getStateUsing(fn ($record) =>
                        $record->qty * ($record->product?->price ?? 0)
                    ),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
