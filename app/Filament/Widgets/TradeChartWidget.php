<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TradeChartWidget extends ChartWidget
{
    protected ?string $heading = 'PnL Performance';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    /**
     * 🧩 Default filter: hari ini
     */
    public ?string $filter = 'today'; // ✅ non-static!

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'this_week' => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
            'custom' => 'Custom Range',
        ];
    }

    protected function getData(): array
    {
        $userId = Auth::id();
        $filter = $this->filter; // ambil filter aktif

        // Tentukan rentang tanggal berdasarkan filter
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();

        switch ($filter) {
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'custom':
                // Nanti bisa kamu buat input date manual di sini
                // misalnya berdasarkan properti date range
                // untuk sementara kita biarkan sama seperti this_month
                $startDate = now()->subMonths(6);
                $endDate = now();
                break;
            default:
                // Hari ini (default)
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
        }

        // Ambil data berdasarkan tanggal exit_time
        $query = Trade::where('user_id', $userId)
            ->whereNotNull('exit_price')
            ->whereBetween('exit_time', [$startDate, $endDate]);

        // Jika filter bulanan → group by hari
        if ($filter === 'this_month' || $filter === 'this_week' || $filter === 'today') {
            $dataPoints = $query
                ->select(
                    DB::raw('DATE(exit_time) as date'),
                    DB::raw('SUM(pnl_value) as total_pnl')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = $dataPoints->map(fn($item) => Carbon::parse($item->date)->format('d M'))->toArray();
            $data = $dataPoints->pluck('total_pnl')->toArray();
        } else {
            // Custom → tampilkan per bulan (6 bulan terakhir)
            $dataPoints = $query
                ->select(
                    DB::raw('DATE_FORMAT(exit_time, "%Y-%m") as month'),
                    DB::raw('SUM(pnl_value) as total_pnl')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = $dataPoints->map(fn($item) => Carbon::parse($item->month . '-01')->format('M Y'))->toArray();
            $data = $dataPoints->pluck('total_pnl')->toArray();
        }

        return [
            'datasets' => [
                [
                    'label' => 'PnL',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
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
