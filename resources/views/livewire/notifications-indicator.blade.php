<div class="relative ml-3">
    <button wire:click="$toggle('showNotifications')"
        class="relative p-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <span class="sr-only">View notifications</span>
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if ($unreadCount > 0)
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notifications dropdown -->
    @if ($showNotifications)
        <div
            class="absolute right-0 z-10 w-64 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="py-1">
                <div
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-100">
                    <span>Notifications</span>
                    @if ($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-900">
                            Mark all as read
                        </button>
                    @endif
                </div>
                <div class="overflow-y-auto max-h-64">
                    @if ($notifications->count() > 0)
                        @foreach ($notifications as $notification)
                            <a href="{{ $notification->data['url'] }}"
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $notification->unread() ? 'bg-gray-50' : '' }}">
                                <div class="flex justify-between">
                                    <span>{{ $notification->data['message'] }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="px-4 py-2 text-sm text-gray-500">
                            No notifications found
                        </div>
                    @endif
                </div>
                <div class="px-4 py-2 text-sm font-medium text-indigo-600 border-t border-gray-100">
                    <a href="{{ route('notifications.index') }}">View all notifications</a>
                </div>
            </div>
        </div>
    @endif
</div>
