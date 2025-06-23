<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AdressRelationManager;

use App\Models\Order;
use App\Models\Products;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\BulkActionGroup;

use Illuminate\Support\Number;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Forms\Key;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\disableOptionsWhenSelectedInSiblingRepeaterItems;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;



use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Resources\Resource;


use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                       Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('payment_method')
                        ->options([
                            'stripe' => 'Stripe',
                            'cash' => 'Cash on Delivery'
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
                            'new' => 'New',
                            'procesing' => 'Procesing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled'
                        ])
                        ->colors([
                            'new'=>'info',
                            'processing'=>'success',
                            'shipped'=>'warning',
                            'delivered'=>'primary',
                            'cancelled'=>'danger'

                        ])
                        ->icons([
                            'new' => 'heroicon-o-shopping-cart',
                            'processing' => 'heroicon-o-cog',
                            'shipped' => 'heroicon-o-truck',
                            'delivered' => 'heroicon-o-check-circle',
                            'cancelled' => 'heroicon-o-x-circle'
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
                        select::make('shipping_method')
                        ->options([
                            'FedEx' => 'FedEx',
                            'Kenttex' => 'Kenttex',
                            'EMS' => 'EMS',

                        ]),
                        Textarea::make('notes')
                        ->columnSpanFull()
                    ])->columns(2),   
                    
                    section::make('Order Items')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->relationship('product','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(
                                fn ($state,Set $set)=>$set('unit_amount',Products::find($state)->price ?? 0)
                            )
                            ->afterStateUpdated(
                                fn ($state,Set $set)=>$set('total_amount',Products::find($state)->price ?? 0)
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
                            
                            ->columnSpan(3),

                            TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->columnSpan(3)
                            



                            
                        ])->columns(12),

                        placeholder::make('grand_total_placeholder')
                        ->label('Grand Total')
                        
                        ->content(function(Get $get,Set $set ){
                            $total=0;
                            if(!$repeaters = $get('items')){
                                return $total;
                            }
                            foreach($repeaters as $key => $repeater){
                                $total += $get("items.{$key}.total_amount");
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

                Textcolumn::make('shipping_method')
                ->searchable()
                ->sortable(),


                SelectColumn::make('status')
                ->options([
                    'new' => 'New',
                    'procesing' => 'Procesing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
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
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
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
            AdressRelationManager::class
        ];
    }
     public static function getNavigationBadge(): ?string
    {
       return static::getModel()::count();
    }
    //  public static function getRelations(): ?string|array|null
    // {
    //   return static::getModel()::count()>10?'danger':
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
