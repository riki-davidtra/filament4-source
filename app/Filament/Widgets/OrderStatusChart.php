<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class OrderStatusChart extends ChartWidget
{
    use HasWidgetShield;

    protected ?string $heading  = 'Order Status Chart';
    protected static ?int $sort = 6;
    // protected int | string | array $columnSpan = 'full'; 

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label'           => 'Total Orders',
                    'data'            => [50, 30, 20],
                    'backgroundColor' => ['#fbbf24', '#34d399', '#f87171'],
                ],
            ],
            'labels' => ['Pending', 'Finished', 'Canceled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
