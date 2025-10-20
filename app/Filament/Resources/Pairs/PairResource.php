<?php

namespace App\Filament\Resources\Pairs;

use App\Filament\Resources\Pairs\Pages\CreatePair;
use App\Filament\Resources\Pairs\Pages\EditPair;
use App\Filament\Resources\Pairs\Pages\ListPairs;
use App\Filament\Resources\Pairs\Schemas\PairForm;
use App\Filament\Resources\Pairs\Tables\PairsTable;
use App\Models\Pair;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PairResource extends Resource
{
    protected static ?string $model = Pair::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PairForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PairsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPairs::route('/'),
            'create' => CreatePair::route('/create'),
            'edit' => EditPair::route('/{record}/edit'),
        ];
    }
}
