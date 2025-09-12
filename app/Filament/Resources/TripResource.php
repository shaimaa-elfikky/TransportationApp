<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TripResource\Forms\TripForm;
use App\Filament\Resources\TripResource\Pages;
use App\Models\Trip;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
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
        return $form->schema(TripForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('driver.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vehicle.make')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('destination')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->getStateUsing(fn ($record) => $record->status)
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([

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
        return parent::getEloquentQuery()->with(['company', 'driver', 'vehicle']);
    }
}
