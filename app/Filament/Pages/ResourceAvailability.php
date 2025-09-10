<?php

namespace App\Filament\Pages;

use App\Models\Driver;
use App\Models\Vehicle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;

class ResourceAvailability extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'filament.pages.resource-availability';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('startTime')
                    ->label('Period Start Time')
                    ->required(),
                DateTimePicker::make('endTime')
                    ->label('Period End Time')
                    ->required()
                    ->afterOrEqual('startTime'),
            ])
            ->statePath('data');
    }

    #[Computed]
    public function getAvailableResourcesProperty(): array
    {
        $startTime = $this->data['startTime'] ?? null;
        $endTime = $this->data['endTime'] ?? null;

        if (empty($startTime) || empty($endTime)) {
            return [
                'drivers' => collect(),
                'vehicles' => collect(),
            ];
        }

        $availableDrivers = Driver::query()
            ->whereDoesntHave('trips', function (Builder $query) use ($startTime, $endTime) {
                $query
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->orderBy('name')
            ->get();

        $availableVehicles = Vehicle::query()
            ->whereDoesntHave('trips', function (Builder $query) use ($startTime, $endTime) {
                $query
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->orderBy('make')
            ->get();

        return [
            'drivers' => $availableDrivers,
            'vehicles' => $availableVehicles,
        ];
    }
}
