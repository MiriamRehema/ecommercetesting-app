<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceRequestResource\Pages;
use App\Filament\Resources\ServiceRequestResource;
use App\Filament\Resources\ServiceRequestResource\RelationManagers;
use App\Models\Service_request;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;


use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\BulkActionGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Forms\Key;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\disableOptionsWhenSelectedInSiblingRepeaterItems;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = Service_request::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Service requests Information')->schema([
                       Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('payment_method')
                        ->options([
                            'mpsesa' => 'Mpesa',
                            'cheque' => 'Cheque'
                        ])
                        ->required(),


                        Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed'
                        ])
                        ->default('pending')
                        ->required(),

                        ToggleButtons::make('status')
                        ->inline()
                        ->required()
                        ->default('new')
                        ->options([
                            
                            'requested' => 'Requested',
                            'in progress' => 'In Progress',
                            'completed' => 'Completed',
                            
                        ])
                        ->colors([
                            'requested'=>'info',
                            'in progress'=>'success',
                            
                            'completed'=>'primary',
                            

                        ])
                        ->icons([
                            'requested' => 'heroicon-o-shopping-cart',
                            'in progress' => 'heroicon-o-cog',
                            'completed' => 'heroicon-o-truck',
                            
                        ]),
                        select::make('currency')
                        ->options([
                            'KSH' => 'Kenyan Shilling',
                            'USD' => 'US Dollar',
                            'EUR' => 'Euro',
                            'GBP' => 'British Pound',
                            'INR' => 'Indian Rupee',
                            'KSH' => 'Kenyan Shilling',
                        ]),
                        
                        
                    ])->columns(2),   
                    
                    section::make('Order Services')->schema([
                        Repeater::make('services')
                        ->relationship()
                        ->schema([
                            Select::make('service_id')
                            ->relationship('service','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(
                                fn ($state,Set $set)=>$set('unit_amount',Service::find($state)?->price ?? 0)
                            )
                            ->afterStateUpdated(
                                fn ($state,Set $set)=>$set('total_amount',Service::find($state)?->price ?? 0)
                            ),
                            

                            TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->reactive()
                            ->afterStateUpdated(fn($state, Set $set,Get $get)=> $set('total_amount',$state* $get('unit_amount')))
                            ->columnSpan(2),

                            TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                           ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),

                            TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->dehydrated()
                            ->columnSpan(3)
                            



                            
                        ])->columns(12),

                        placeholder::make('grand_total_placeholder')
                        ->label('Grand Total')
                        
                        ->content(function(Get $get,Set $set ){
                            $total=0;
                            if(!$repeaters = $get('services')){
                                return $total;
                            }
                            foreach($repeaters as $key => $repeater){
                                $total += $get("services.{$key}.total_amount");
                            }
                            $set('grand_total',$total);
                            // return '$' . number_format($total, 2);
                            
                            
                            return Number::currency($total,'$');
                        }),
                       
                        
                        Hidden::make('grand_total')
                        ->default(0),


                    ])

                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Textcolumn::make('user.name')
                ->label('customer')
                ->sortable()
                ->searchable(),

                Textcolumn::make('grand_total')
                ->numeric()
                ->sortable()
                ->money(),

                Textcolumn::make('payment_method')
                ->searchable()
                ->sortable(),

                Textcolumn::make('payment_status')
                ->searchable()
                ->sortable(),
                
                Textcolumn::make('currency')
                ->searchable()
                ->sortable(),

               


                SelectColumn::make('status')
                ->options([
                            'requested' => 'Requested',
                            'in progress' => 'In Progress',
                            'completed' => 'Completed',
                            
                        ])
                ->searchable()
                ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRequests::route('/'),
            'create' => Pages\CreateServiceRequest::route('/create'),
            'view' => Pages\ViewServiceRequest::route('/{record}'),
            'edit' => Pages\EditServiceRequest::route('/{record}/edit'),
        ];
    }
}
