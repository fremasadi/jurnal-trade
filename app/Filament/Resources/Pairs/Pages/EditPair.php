<?php

namespace App\Filament\Resources\Pairs\Pages;

use App\Filament\Resources\Pairs\PairResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPair extends EditRecord
{
    protected static string $resource = PairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
