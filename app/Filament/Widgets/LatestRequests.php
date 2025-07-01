<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Service_request;
use App\Filament\Resources\ServiceRequestResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use App\Models\User;
use App\Models\Order_service;

use Filament\Widgets\TableWidget as BaseWidget;

class LatestRequests extends BaseWidget
{

    protected int | string | array  $columnSpan = 'full';
    protected static ?int $sort =2;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
            ServiceRequestResource::getEloquentQuery())
            ->defaultPaginationPageOption(4)
            ->defaultSort('created_at','desc')
                
            
            ->columns([
                TextColumn::make('id')
                ->label('OrderId')
                ->searchable(),

                TextColumn::make('user.name')
                
                ->searchable(),



                TextColumn::make('grand_total')
                ->money(),
               

                TextColumn::make('status')
                ->badge()
                ->color(fn(string $state):string=>match($state){
                    'requested'=>'info',
                            'in_progress'=>'success',
                            
                            'completed'=>'primary',
                            'cancelled'=>'danger'

                })
                ->icon(fn(string $state):string=>match($state){
                    'requested' => 'heroicon-o-shopping-cart',
                    'in_progress' => 'heroicon-o-cog',
                    'completed' => 'heroicon-o-truck',
                    'cancelled' => 'heroicon-o-x-circle',

                })
                ->sortable(),

                TextColumn::make('payment_method')
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_method')
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_status')
                ->sortable()
                ->badge()
                ->searchable(),

                TextColumn::make('created_at')
                ->label('Order_date')
                ->dateTime()
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_method')
                ->sortable()
                ->searchable()
            ])
            ->actions([
                Action::make('View Requests')
                 ->url(fn(Service_request $record):string=>ServiceRequestResource::getUrl('view',['record'=>$record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
            ]);


    }
}
