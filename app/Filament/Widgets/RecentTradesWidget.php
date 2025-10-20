<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentTradesWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 6;

    protected static ?string $heading = 'Recent Trades';

    public function table(Table $table): Table
    {
        $userId = Auth::id();

        return $table
            ->query(
                Trade::query()
                    ->where('user_id', $userId)
                    ->whereNotNull('exit_price')
                    ->with(['pair', 'mentor'])
                    ->latest('exit_time')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('pair.symbol_name')
                    ->label('Pair')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('direction')
                    ->label('Direction')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'buy' => 'success',
                        'sell' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                Tables\Columns\TextColumn::make('entry_price')
                    ->label('Entry')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state, 5)),

                Tables\Columns\TextColumn::make('exit_price')
                    ->label('Exit')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state, 5)),

                Tables\Columns\TextColumn::make('result')
                    ->label('Result')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'win' => 'success',
                        'loss' => 'danger',
                        'break_even' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('pnl_value')
                    ->label('PnL')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('exit_time')
                    ->label('Closed At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('exit_time', 'desc');
    }
}