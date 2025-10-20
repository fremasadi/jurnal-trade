<?php

namespace App\Filament\Resources\Pairs\Pages;

use App\Filament\Resources\Pairs\PairResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListPairs extends ListRecords
{
    protected static string $resource = PairResource::class;

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
