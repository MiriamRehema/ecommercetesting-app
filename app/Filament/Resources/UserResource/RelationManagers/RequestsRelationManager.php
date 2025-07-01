<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Models\Service_request;
use App\Filament\Resources\ServiceRequestResource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'requests';

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
                ->label('Request ID')
                ->searchable(),

                TextColumn::make('grand_total')
                ->label('Grand Total')
                ->money(),
               

                TextColumn::make('status')
                ->badge()
                ->color(fn(string $state): string => match($state) {
                    'requested' => 'info',
                    'in_progress' => 'warning',
                    'completed' => 'success',
                    'cancelled' => 'danger',
            
                })
                ->icon(fn(string $state): string => match($state) {
                    'requested' => 'heroicon-m-sparkles',
                    'in_progress' => 'heroicon-m-arrow-path',
                    'completed' => 'heroicon-m-check-badge',
                    'cancelled' => 'heroicon-m-x-circle',
                    
                })
                ->sortable(),

                TextColumn::make('payment_method')
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_status')
                ->sortable()
                ->badge()
                ->searchable(),

                TextColumn::make('created_at')
                ->label('Requested At')
                ->dateTime()
                ->searchable(),

                




            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('View Order')
                ->url(fn(Service_request $record):string=>ServiceRequestResource::getUrl('view',['record'=>$record]))
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
