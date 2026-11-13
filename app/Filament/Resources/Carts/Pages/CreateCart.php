<?php

namespace App\Filament\Resources\Carts\Pages;

use App\Filament\Resources\Carts\CartResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCart extends CreateRecord
{
    protected static string $resource = CartResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}