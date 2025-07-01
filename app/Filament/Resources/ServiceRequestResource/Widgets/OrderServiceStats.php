<?php

namespace App\Filament\Resources\ServiceRequestResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Service_request;
use App\Models\Service;
use App\Models\Order_service;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;

class OrderServiceStats extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'data' => Service_request::query()->count(),    
            'datasets' => [
                [
                    'label' => 'Service Requests',
                    'data' => \App\Models\Order_service::query()
                        ->selectRaw('service_id, SUM(quantity) as total_quantity')
                        ->groupBy('service_id')
                        ->pluck('total_quantity')
                        ->toArray(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                    ],
                ],
            ],
            'labels' => \App\Models\Order_service::query()
                ->select('service_id')
                ->distinct()
                ->pluck('service_id')
                ->map(function ($serviceId) {
                    return \App\Models\Service::find($serviceId)->name ?? 'Unknown Service';
                })
                ->toArray(),
                        
        ];


    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],

            'cutout' => '70%',
        ];
    }
   

}
