<?php

namespace App\Filament\Resources\Mentors\Pages;

use App\Filament\Resources\Mentors\MentorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMentor extends CreateRecord
{
    protected static string $resource = MentorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // otomatis ambil ID user login
        return $data;
    }
}
