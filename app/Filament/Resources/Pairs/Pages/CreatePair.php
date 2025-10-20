<?php

namespace App\Filament\Resources\Pairs\Pages;

use App\Filament\Resources\Pairs\PairResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePair extends CreateRecord
{
    protected static string $resource = PairResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // otomatis ambil user login
        return $data;
    }
}
