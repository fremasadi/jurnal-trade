<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class PairPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 6;

    protected static ?string $heading = 'Performance by Pair';

    public function table(Table $table): Table
    {
        $userId = Auth::id();

        return $table
            ->query(
                Trade::query()
                    ->where('user_id', $userId)
                    ->whereNotNull('exit_price')
                    ->whereNotNull('pair_id')
                    ->select('pair_id')
                    ->selectRaw('COUNT(*) as total_trades')
                    ->selectRaw('SUM(CASE WHEN result = "win" THEN 1 ELSE 0 END) as wins')
                    ->selectRaw('SUM(CASE WHEN result = "loss" THEN 1 ELSE 0 END) as losses')
                    ->selectRaw('SUM(pnl_value) as total_pnl')
                    ->selectRaw('AVG(pnl_value) as avg_pnl')
                    ->groupBy('pair_id')
                    ->with('pair')
            )
            ->columns([
                Tables\Columns\TextColumn::make('pair.symbol_name')
                    ->label('Pair')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('total_trades')
                    ->label('Trades')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('wins')
                    ->label('W/L')
                    ->formatStateUsing(fn ($record) =>
                        $record->wins . '/' . $record->losses
                    )
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('win_rate')
                    ->label('Win Rate')
                    ->state(function ($record) {
                        return $record->total_trades > 0
                            ? ($record->wins / $record->total_trades) * 100
                            : 0;
                    })
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state >= 50 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('total_pnl')
                    ->label('Total PnL')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->sortable()
                    ->alignEnd()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('avg_pnl')
                    ->label('Avg PnL')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->sortable()
                    ->alignEnd()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
            ])
            ->defaultSort('total_pnl', 'desc');
    }
}