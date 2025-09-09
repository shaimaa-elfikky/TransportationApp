<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TripResource\Pages;
use App\Models\Trip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function getNavigationGroup(): string
    {

        return __('Trips Mangements');
    }

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Trip Details')
                ->schema([
                    Forms\Components\Select::make('company_id')
                        ->relationship('company', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(function ($set) {
                            $set('driver_id', null);
                            $set('vehicle_id', null);
                        }),
                    Forms\Components\Select::make('driver_id')
                        ->relationship('driver', 'full_name', function (
                            Builder $query,
                            Forms\Get $get,
                        ) {
                            return $query->where(
                                'company_id',
                                $get('company_id'),
                            );
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('vehicle_id')
                        ->relationship('vehicle', 'license_plate', function (
                            Builder $query,
                            Forms\Get $get,
                        ) {
                            return $query->where(
                                'company_id',
                                $get('company_id'),
                            );
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('name')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull()
                        ->default(null),
                ])
                ->columns(2),

            Forms\Components\Section::make('Route & Schedule')
                ->schema([
                    Forms\Components\TextInput::make('origin')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('destination')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DateTimePicker::make('start_time')
                        ->required()
                        ->seconds(false)
                        ->native(false),
                    Forms\Components\DateTimePicker::make('end_time')
                        ->required()
                        ->seconds(false)
                        ->native(false)
                        ->afterOrEqual('start_time'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'scheduled' => 'Scheduled',
                            'ongoing' => 'Ongoing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('scheduled'),
                ])
                ->columns(2),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('driver.full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Driver'),
                Tables\Columns\TextColumn::make('vehicle.license_plate')
                    ->searchable()
                    ->sortable()
                    ->label('Vehicle'),
                Tables\Columns\TextColumn::make('origin')->searchable(),
                Tables\Columns\TextColumn::make('destination')->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
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
                Tables\Filters\SelectFilter::make('driver_id')
                    ->relationship('driver', 'full_name')
                    ->searchable()
                    ->preload()
                    ->label('Driver'),
                Tables\Filters\SelectFilter::make('vehicle_id')
                    ->relationship('vehicle', 'license_plate')
                    ->searchable()
                    ->preload()
                    ->label('Vehicle'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled'),
                Tables\Filters\Filter::make('upcoming_trips')
                    ->query(function (Builder $query) {
                        $query
                            ->where('start_time', '>', now())
                            ->where('status', 'scheduled');
                    })
                    ->label('Upcoming Trips')
                    ->toggle(),
                Tables\Filters\Filter::make('past_trips')
                    ->query(function (Builder $query) {
                        $query
                            ->where('end_time', '<', now())
                            ->where('status', '!=', 'cancelled');
                    })
                    ->label('Past Trips')
                    ->toggle(),
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
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['company', 'driver', 'vehicle']); // Eager loading
    }
}