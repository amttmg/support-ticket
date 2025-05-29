<x-filament::widget>
    <x-filament::card class="!rounded-xl overflow-hidden">
        <div class="mb-2  bg-gradient-to-r from-primary-500 to-primary-600">
            <div class="flex items-center justify-between">
                <h2 class="flex items-center space-x-2 text-xl font-bold tracking-tight text-black">
                    <x-heroicon-o-user-group class="w-5 h-5" />
                    <span>Your can access tickets of : </span>
                </h2>

            </div>
        </div>

        <div class="px-1 py-1">
            @if ($supportUnits = $this->getSupportUnits())
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($supportUnits as $unit)
                        <div
                            class="relative p-4 transition-colors border rounded-lg group hover:border-primary-300 hover:shadow-sm">
                            <div class="flex items-start space-x-3">

                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $unit->name }}</div>


                                </div>
                            </div>
                            {{-- <a href="{{ route('filament.resources.support-units.view', $unit) }}"
                                class="absolute inset-0 z-10" aria-label="View {{ $unit->name }}"></a> --}}
                        </div>
                    @endforeach
                </div>

            @endif
        </div>


    </x-filament::card>
</x-filament::widget>
