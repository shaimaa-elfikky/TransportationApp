<x-filament-panels::page>

    <x-filament::section>
        <x-slot name="heading">
            Select a Time Range
        </x-slot>

        <div wire:poll.keep-alive>
            {{ $this->form }}
        </div>
    </x-filament::section>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- The card for available drivers --}}
        <x-filament::section>
            <x-slot name="heading">
                Available Drivers ({{ count($this->availableResources['drivers']) }})
            </x-slot>
            
    
            <div class="fi-ta-content overflow-x-auto">
                <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6">Name</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6">Phone</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                        @forelse ($this->availableResources['drivers'] as $driver)
                            <tr class="fi-ta-row">
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="px-3 py-4 sm:px-6 flex items-center justify-center">{{ $driver->name }}</div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="px-3 py-4 sm:px-6 flex items-center justify-center">{{ $driver->phone }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-3 py-4 sm:px-6 text-center text-gray-500">
                                    No drivers available for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        {{-- The card for available vehicles --}}
        <x-filament::section>
            <x-slot name="heading">
                Available Vehicles ({{ count($this->availableResources['vehicles']) }})
            </x-slot>
            
            <div class="fi-ta-content overflow-x-auto">
                <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6">Make</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6">Model</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6">License Plate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                        @forelse ($this->availableResources['vehicles'] as $vehicle)
                            <tr class="fi-ta-row">
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="px-3 py-4 sm:px-6 flex items-center justify-center">{{ $vehicle->make }}</div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="px-3 py-4 sm:px-6 flex items-center justify-center">{{ $vehicle->model }}</div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="px-3 py-4 sm:px-6 flex items-center justify-center">{{ $vehicle->license_plate }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 sm:px-6 text-center text-gray-500">
                                    No vehicles available for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>

</x-filament-panels::page>