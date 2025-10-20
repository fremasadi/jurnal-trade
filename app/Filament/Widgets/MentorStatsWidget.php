<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class MentorStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 4;

    protected static ?string $heading = 'Mentor Performance';

    public function table(Table $table): Table
    {
        $userId = Auth::id();

        return $table
            ->query(
                Trade::query()
                    ->where('user_id', $userId)
                    ->whereNotNull('exit_price')
                    ->whereNotNull('mentor_id')
                    ->select('mentor_id')
                    ->selectRaw('COUNT(*) as total_trades')
                    ->selectRaw('SUM(CASE WHEN result = "win" THEN 1 ELSE 0 END) as wins')
                    ->selectRaw('SUM(pnl_value) as total_pnl')
                    ->groupBy('mentor_id')
                    ->with('mentor')
            )
            ->columns([
                Tables\Columns\TextColumn::make('mentor.name')
                    ->label('Mentor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_trades')
                    ->label('Trades')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('wins')
                    ->label('Win Rate')
                    ->formatStateUsing(function ($record) {
                        $winRate = $record->total_trades > 0
                            ? ($record->wins / $record->total_trades) * 100
                            : 0;
                        return number_format($winRate, 1) . '%';
                    })
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($record) =>
                        ($record->total_trades > 0 && ($record->wins / $record->total_trades) >= 0.5)
                            ? 'success'
                            : 'warning'
                    ),

                Tables\Columns\TextColumn::make('total_pnl')
                    ->label('Total PnL')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->sortable()
                    ->alignEnd()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
            ])
            ->defaultSort('total_pnl', 'desc');
    }
}