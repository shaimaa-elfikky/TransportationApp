<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages\CreateVehicle;
use App\Filament\Resources\VehicleResource\Pages\EditVehicle;
use App\Filament\Resources\VehicleResource\Pages\ListVehicles;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function getNavigationGroup(): string
    {

        return __('Vehicles Mangements');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('company_id')
                        ->relationship('company', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('make')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('model')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('license_plate')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('year')
                        ->required()
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(date('Y') + 1),
                    Forms\Components\Select::make('type')
                        ->options([
                            'Truck' => 'Truck',
                            'Van' => 'Van',
                            'Car' => 'Car',
                            'Motorcycle' => 'Motorcycle',
                            'Bus' => 'Bus',
                        ])
                        ->default('Truck'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('make')->searchable(),
                Tables\Columns\TextColumn::make('model')->searchable(),
                Tables\Columns\TextColumn::make('license_plate')->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Company'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Truck' => 'Truck',
                        'Van' => 'Van',
                        'Car' => 'Car',
                        'Motorcycle' => 'Motorcycle',
                        'Bus' => 'Bus',
                    ])
                    ->label('Type'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'edit' => EditVehicle::route('/{record}/edit'),
        ];
    }
}
