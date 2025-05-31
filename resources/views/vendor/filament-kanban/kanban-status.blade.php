@props(['status'])

<div class="w-72 flex-shrink-0 mb-5 flex flex-col min-h-[80vh] md:min-h-[65vh]">
    @include(static::$headerView)

    <div data-status-id="{{ $status['id'] }}"
        class="flex flex-col flex-1 gap-2 p-3 overflow-y-auto bg-gray-200 dark:bg-gray-800 rounded-xl"
        style="max-height: calc(100vh - 300px); min-height: calc(100vh - 300px);">
        @foreach ($status['records'] as $record)
            @include(static::$recordView)
        @endforeach
    </div>
</div>
