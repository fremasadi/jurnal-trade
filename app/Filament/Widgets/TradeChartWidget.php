<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeChartWidget extends ChartWidget
{
    protected ?string $heading = 'Monthly PnL Performance';
    protected static ?int $sort = 2;

    // Widget mengambil 8 kolom
    protected int | string | array $columnSpan = 8;

    protected function getData(): array
    {
        $userId = Auth::id();

        // Ambil data PnL per bulan (6 bulan terakhir)
        $monthlyPnL = Trade::where('user_id', $userId)
            ->whereNotNull('exit_price')
            ->whereNotNull('exit_time')
            ->where('exit_time', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(exit_time, "%Y-%m") as month'),
                DB::raw('SUM(pnl_value) as total_pnl')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $monthlyPnL->map(function ($item) {
            return \Carbon\Carbon::parse($item->month . '-01')->format('M Y');
        })->toArray();

        $data = $monthlyPnL->pluck('total_pnl')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'PnL',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}