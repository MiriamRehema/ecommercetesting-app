<?php

namespace App\Filament\Resources\ServiceRequestResource\Pages;

use App\Filament\Resources\ServiceRequestResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\ServiceRequestResource\Widgets\OrderServiceStats;
use Filament\Resources\Pages\ListRecords;

class ListServiceRequests extends ListRecords
{
    protected static string $resource = ServiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];

    }
    protected function getHeaderWidgets(): array
    {
        return [
            OrderServiceStats::class,
        ];
    }
    public function getTabs(): array
    {
        return [
            null=>Tab::make('All'),
            'requested'=>Tab::make('Requested')
                ->query(fn ( $query) => $query->where('status', 'requested')),
            'in_progress'=>Tab::make('In Progress')
                ->query(fn ( $query) => $query->where('status', 'in_progress')), 
            'completed'=>Tab::make('Completed')
                ->query(fn ( $query) => $query->where('status', 'completed')),
            'cancelled'=>Tab::make('Cancelled')
                ->query(fn ( $query) => $query->where('status', 'cancelled')),
        ];
    }
   
}
