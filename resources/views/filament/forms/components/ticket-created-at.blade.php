{{-- resources/views/filament/forms/components/ticket-created-at.blade.php --}}
<div class="space-y-1">
    <label class="text-xs font-medium text-gray-500">
        {{ $getLabel() }}
    </label>
    <div class="flex items-center space-x-2">
        <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
        <span class="text-sm text-gray-900">
            {{ $getRecord()->created_at->format('M d, Y') }}
            <span class="text-gray-500">
                at {{ $getRecord()->created_at->format('h:i A') }}
            </span>
        </span>
    </div>
</div>
