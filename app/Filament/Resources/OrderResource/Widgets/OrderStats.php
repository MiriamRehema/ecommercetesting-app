<?php

namespace App\Filament\Resources\OrderResource\Widgets;
use App\Models\Order;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;


class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
           Stat::make('New Orders',Order::query()->where('status','new')->count())
           ->chart([1,3,5,10,15,20,35,40])
           ->color('success')
           ->icon('heroicon-o-shopping-cart'),
           Stat::make('Order procesing',Order::query()->where('status','procesing')->count())
           ->chart([1,3,5,10,15,20,35,40])
           ->color('warning')
           ->icon('heroicon-o-cog'),
           Stat::make(' Order shipped',Order::query()->where('status','shipped')->count())
           ->chart([1,3,5,10,15,20,35,40])
           ->color('info')
           ->icon('heroicon-o-truck',IconPosition::Before),
           Stat::make('Average Price',Number::currency(Order::query()->avg('grand_total')), '$')
            ->chart([1,3,5,10,15,20,35,40])  
           ->color('danger')    
           ->icon('heroicon-o-currency-dollar'),
           
        ];
    }

}
