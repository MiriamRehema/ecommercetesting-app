<?php

namespace App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Order;
use App\Filament\Resources\OrderResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                ->label('OrderId')
                ->searchable(),

                TextColumn::make('grand_total')
                ->money(),
               

                TextColumn::make('status')
                ->badge()
                ->color(fn(string $state):string=>match($state){
                    'new'=>'info',
                    'procesing'=>'warning',
                    'shipped'=>'success',
                    'delivered'=>'delivered',
                    'cancelled'=>'danger'

                })
                ->icon(fn(string $state):string=>match($state){
                    'new'=>'heroicon-m-sparkles',
                    'procesing'=>'heroicon-m-arrow-path',
                    'shipped'=>'heroicon-m-truck',
                    'delivered'=>'heroicon-m-check-badge',
                    'cancelled'=>'heroicon-m-x-circle'

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
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('View Order')
                ->url(fn(Order $record):string=>OrderResource::getUrl('view',['record'=>$record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
