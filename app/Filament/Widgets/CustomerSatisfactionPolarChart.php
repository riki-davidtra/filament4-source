<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class CustomerSatisfactionPolarChart extends ChartWidget
{
    use HasWidgetShield;

    protected ?string $heading  = 'Customer Satisfaction Polar Chart';
    protected static ?int $sort = 5;
    // protected int | string | array $columnSpan = 'full'; 

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label'           => 'Satisfaction Score',
                    'data'            => [85, 70, 60, 90, 75],
                    'backgroundColor' => [
                        'rgba(52, 144, 220, 0.5)',
                        'rgba(246, 109, 155, 0.5)',
                        'rgba(102, 187, 106, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                    ],
                ],
            ],
            'labels' => ['Product', 'Service', 'Delivery', 'Price', 'Support'],
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}
