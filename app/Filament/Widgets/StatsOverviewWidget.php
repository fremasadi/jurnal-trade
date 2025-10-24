<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 12;

    protected function getStats(): array
    {
        $userId = Auth::id();

        $trades = Trade::where('user_id', $userId)
            ->whereNotNull('exit_price')
            ->get();

        $totalTrades = $trades->count();
        $totalPnL = $trades->sum('pnl_value');

        // Hitung trade berdasarkan result
        $winTrades = $trades->whereIn('result', ['Profit', 'profit', 'Win', 'win'])->count();
        $lossTrades = $trades->whereIn('result', ['Loss', 'loss'])->count();
        $breakEvenTrades = $trades->whereIn('result', ['Break Even', 'break_even', 'break even'])->count();

        // Hitung win rate
        $winRate = $totalTrades > 0 ? ($winTrades / $totalTrades) * 100 : 0;

        return [
            Stat::make('Total Trades', $totalTrades)
                ->description('Total closed trades')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make('Total PnL', number_format($totalPnL, 2))
                ->description($totalPnL >= 0 ? 'Profit' : 'Loss')
                ->descriptionIcon($totalPnL >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($totalPnL >= 0 ? 'success' : 'danger'),

            Stat::make('Win Rate', number_format($winRate, 2) . '%')
                ->description("Win: {$winTrades} | Loss: {$lossTrades}")
                ->descriptionIcon('heroicon-m-trophy')
                ->color($winRate >= 50 ? 'success' : 'warning'),

            Stat::make('Break Even', $breakEvenTrades)
                ->description('No profit/loss trades')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->color('gray'),
        ];
    }
}
