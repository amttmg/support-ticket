@if ($getLabel())
    <div class="block mb-2 text-sm font-medium text-gray-700">
        {{ $getLabel() }}
    </div>
@endif
<div>
    @php
        $ticket = $getRecord();
        $activities = $ticket->activities()->get();
    @endphp

    @if ($activities->count())
        <div class="space-y-2">
            @foreach ($activities as $activity)
                @php
                    $causerName = $activity->causer?->name ?? 'System';
                    $avatar =
                        $activity->causer?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($causerName);

                    $statusOld = $activity->properties['old']['status.name'] ?? null;
                    $statusNew = $activity->properties['attributes']['status.name'] ?? null;
                @endphp

                <div class="flex gap-3 p-2 border rounded bg-gray-50">
                    <!-- Avatar -->
                    <img src="{{ $avatar }}" alt="Avatar" class="object-cover w-8 h-8 border rounded-full" />

                    <!-- Details -->
                    <div class="flex-1 text-sm text-gray-900">
                        <strong>{{ $causerName }}:</strong>
                        <span class="text-gray-600">
                            @if ($statusOld && $statusNew)
                                Changed status to <strong class="capitalize">{{ $statusNew }}</strong>
                            @else
                                {{ $activity->description }}
                            @endif
                        </span>

                        @if ($statusOld && $statusNew)
                            <div class="mt-1 text-xs text-gray-700">
                                <span class="text-red-500">{{ $statusOld }}</span> →
                                <span class="text-green-600">{{ $statusNew }}</span>
                            </div>
                        @endif

                        <div class="mt-1 text-xs text-gray-500">
                            {{ $activity->created_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-400">No history available.</p>
    @endif
</div>
