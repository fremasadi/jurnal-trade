<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class TradingDashboard extends BaseDashboard
{
    protected static ?string $title = 'Trading Dashboard';
    protected static ?int $navigationSort = -100;

    // Atur grid columns: 12 kolom
    public function getColumns(): int|array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            // Statistik ringkas user: total trades, total PnL, winrate
            \App\Filament\Widgets\StatsOverviewWidget::class,

            // Chart PnL per bulan
            \App\Filament\Widgets\TradeChartWidget::class,

        
        ];
    }
}