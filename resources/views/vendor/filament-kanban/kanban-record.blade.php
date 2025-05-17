<div id="{{ $record->getKey() }}" wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="group relative px-4 py-3 mb-2 font-medium text-gray-700 bg-white rounded-lg shadow-sm record dark:bg-gray-700 dark:text-gray-200 cursor-grab hover:shadow-md transition-shadow duration-200 border-l-4 border-{{ $record->priority_color }}-500"
    @if ($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}, true) < 3) x-data
        x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        " @endif>

    <!-- Ticket Title -->
    <div class="flex items-start justify-between">
        <h3 class="pr-2 text-sm font-semibold line-clamp-2">
            {{ $record->{static::$recordTitleAttribute} }}
        </h3>
        <span class="text-xs text-gray-500 dark:text-gray-400">
            #{{ $record->id }}
        </span>
    </div>

    <!-- Branch Name -->
    @if ($record->branch)
        <div class="flex items-center mt-1">

            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono truncate max-w-[180px]">
                {{ $record->branch->formatted_name }}
            </span>
        </div>
    @endif

    <!-- Ticket Metadata -->
    <div class="flex items-center justify-between mt-2 text-xs">
        <!-- Assignee Avatar -->
        @if ($record->agents && $record->agents->count())
            <div class="flex items-center -space-x-2">
                @foreach ($record->agents as $agent)
                    <div class="relative group">
                        <span
                            class="flex items-center justify-center w-6 h-6 text-xs font-bold text-gray-700 uppercase transition-all duration-150 bg-gray-200 rounded-full dark:bg-gray-600 dark:text-gray-200 hover:z-10 hover:ring-2 hover:ring-white dark:hover:ring-gray-700"
                            title="{{ $agent->name }}">
                            {{ Str::substr($agent->name, 0, 1) }}
                        </span>
                        <div
                            class="absolute px-2 py-1 mb-1 text-xs font-medium text-white transition-opacity duration-200 transform -translate-x-1/2 bg-gray-800 rounded opacity-0 pointer-events-none bottom-full left-1/2 whitespace-nowrap group-hover:opacity-100">
                            {{ $agent->name }}
                            <div
                                class="absolute w-2 h-2 -mt-1 rotate-45 -translate-x-1/2 bg-gray-800 top-full left-1/2">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Due Date -->
        @if ($record->created_at)
            <div class="{{ $record->is_overdue ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                <i class="mr-1 far fa-calendar-alt"></i>
                {{ $record->created_at->diffForHumans() }}
            </div>
        @endif
    </div>

    <!-- Hover actions -->
    <div
        class="absolute flex space-x-1 transition-opacity duration-200 opacity-0 top-2 right-2 group-hover:opacity-100">
        <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <i class="text-xs far fa-edit"></i>
        </button>
        <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <i class="text-xs far fa-comment"></i>
        </button>
    </div>
</div>
