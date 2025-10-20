<?php

namespace App\Filament\Resources\Pairs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PairForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('symbol_name')
                    ->label('Symbol')
                    ->required()
                    ->placeholder('contoh: XAUUSD, EURUSD, GBPJPY'),
            ]);
    }
}
