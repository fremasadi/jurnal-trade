<?php

namespace App\Filament\Resources\Trades\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class TradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('mentor.name')
                    ->label('Mentor')
                    
                    ->placeholder('-'),

                TextColumn::make('pair.symbol_name')
                    ->label('Pair')
                    ,

                TextColumn::make('direction')
                    ->badge()
                    ->colors([
                        'success' => 'Buy',
                        'danger' => 'Sell',
                    ]),

                TextColumn::make('entry_price')->numeric(),
                TextColumn::make('exit_price')->numeric(),
                TextColumn::make('lot_size')->numeric(),

                TextColumn::make('result')
                    ->badge()
                    ->colors([
                        'success' => 'Profit',
                        'danger' => 'Loss',
                    ]),

                TextColumn::make('pnl_value')
                    ->numeric()
                    ->prefix('$')
                    ,

                ImageColumn::make('screenshot_img')
                    ->label('Screenshot')
                    ->square()
                    ->size(40),

                TextColumn::make('entry_time')->dateTime(),
                TextColumn::make('exit_time')->dateTime(),

                TextColumn::make('created_at')
                    ->dateTime()
                    
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
        
            
            ;
    }
}
