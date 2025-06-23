<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdressRelationManager extends RelationManager
{
    protected static string $relationship = 'adress';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('first_name')
                ->required()
                ->maxLength(255),

                TextInput::make('last_name')
                ->required()
                ->maxLength(255),

                TextInput::make('phone')
                ->required()
                ->tel()
                ->maxLength(25),

                TextInput::make('city')
                ->required()
                ->maxLength(255),

                TextInput::make('state')
                ->required()
                ->maxLength(255),

                TextInput::make('zip_code')
                ->required()
                ->numeric()
                ->maxLength(10),
                
                Textarea::make('street_address')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('fullname')
                ->label('Full name'),

                TextColumn::make('phone')
                ->label('Phone'),

                TextColumn::make('city')
                ->label('City'),

                TextColumn::make('state')
                ->label('Stae'),

                TextColumn::make('zip_code')
                ->label('Zip Code'),
                TextColumn::make('street_address')
                ->label('Street Adress'),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
