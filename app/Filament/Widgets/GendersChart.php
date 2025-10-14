<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class GendersChart extends ChartWidget
{
    use HasWidgetShield;

    protected ?string $heading  = 'Genders Chart';
    protected static ?int $sort = 4;
    // protected int | string | array $columnSpan = 'full'; 

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label'           => 'Total Genders',
                    'data'            => [120, 80],
                    'backgroundColor' => ['#3490dc', '#f66d9b'],
                ],
            ],
            'labels' => ['Man', 'Woman'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
