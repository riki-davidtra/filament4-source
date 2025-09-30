<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class MonthlySalesChart extends ChartWidget
{
    protected ?string $heading  = 'Monthly Sales Chart';
    protected static ?int $sort = 3;
    // protected int | string | array $columnSpan = 'full'; 

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label'           => 'Penjualan (Rp)',
                    'data'            => [120000, 150000, 100000, 180000, 200000, 170000, 220000, 250000, 210000, 230000, 240000, 260000],
                    'backgroundColor' => '#3b82f6',                                                                                          // warna batang
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
