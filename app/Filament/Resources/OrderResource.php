<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\BulkActionGroup;




use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\disableOptionsWhenSelectedInSiblingRepeaterItems;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;



use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteBulkAction;
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

                        Select::make('payment method')
                        ->options([
                            'stripe' => 'Stripe',
                            'cash' => 'Cash on Delivery',
                        ])
                        ->required(),


                        Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                        ])
                        ->default('pending')
                        ->required(),

                        ToggleButtons::make('status')
                        ->inline()
                        ->required()
                        ->default('new')
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->colors([
                            'new'=>'info',
                            'processing'=>'success',
                            'shipped'=>'warning',
                            'delivered'=>'primary',
                            'cancelled'=>'danger',

                        ])
                        ->icons([
                            'new' => 'heroicon-o-shopping-cart',
                            'processing' => 'heroicon-o-cog',
                            'shipped' => 'heroicon-o-truck',
                            'delivered' => 'heroicon-o-check-circle',
                            'cancelled' => 'heroicon-o-x-circle',
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
                            Select::make('products_id')
                            ->relationship('products','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(
                                fn ($state,Set $set)=>$set('amount',Products::find($state)->price ?? 0)

                            ),
                            

                            TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpan(2),

                            TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpan(3),

                            TextInput::make('total_total')
                            ->numeric()
                            ->required()
                            ->columnSpan(3)



                            
                        ])->columns(12),
                    ])

                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
