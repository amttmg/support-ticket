@if ($getLabel())
    <div class="block mb-2 text-sm font-medium text-gray-700">
        {{ $getLabel() }}
    </div>
@endif

<div x-data="{ editing: false, status: '{{ $getState()->name }}' }" class="inline-flex items-center gap-2">
    {{-- Badge --}}

    <div x-show="!editing" @click="(status !== 'Rejected' && status !== 'Closed') && (editing = true)"
        :class="(status === 'Rejected' || status === 'Closed') ? 'cursor-not-allowed opacity-60' : 'cursor-pointer'"
        :title="(status === 'Rejected' || status === 'Closed') ? 'Editing disabled for this status' : 'Click to edit'">
        <x-filament::badge :color="getStatusColor($getState()->name)">
            <span class="inline-flex items-center gap-1">
                @php
                    $icon = match ($getState()->name) {
                        'Open' => 'heroicon-o-exclamation-circle',
                        'In Progress' => 'heroicon-o-arrow-path',
                        'Resolved' => 'heroicon-o-check-circle',
                        'Rejected' => 'heroicon-o-x-circle',
                        'Closed' => 'heroicon-o-lock-closed',
                        default => null,
                    };
                @endphp

                @if ($icon)
                    @svg($icon, 'w-4 h-4')
                @endif

                <span x-text="status"></span>
            </span>
        </x-filament::badge>
    </div>

    {{-- Dropdown --}}
    <div x-show="editing" x-cloak>
        <select class="text-sm border-gray-300 rounded-md focus:border-primary-500 focus:ring-primary-500"
            wire:change="updateStatus({{ $getRecord()->id }}, $event.target.value)"
            @change="status = $event.target.options[$event.target.selectedIndex].text; editing = false">
            @foreach ($statuses as $status)
                <option value="{{ $status->id }}" @selected($getState()->id === $status->id)>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
