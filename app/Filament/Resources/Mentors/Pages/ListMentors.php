<?php

namespace App\Filament\Resources\Mentors\Pages;

use App\Filament\Resources\Mentors\MentorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class ListMentors extends ListRecords
{
    protected static string $resource = MentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $query = parent::getTableQuery();

        if (Auth::user()->role === 'user') {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }
}
