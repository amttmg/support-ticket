<template>
    <!-- Notifications Dropdown -->
    <div class="relative ml-3">
        <button @click="toggleNotifications"
            class="relative p-1.5 text-gray-500 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">View notifications</span>
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full animate-pulse">
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Notifications dropdown panel -->
        <transition enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0">
            <div v-show="showingNotificationsDropdown"
                class="absolute right-0 z-10 mt-2 origin-top-right bg-white rounded-lg shadow-xl w-80 ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-900">Notifications</h3>
                        <button v-if="unreadCount > 0" @click="markAllAsRead"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                            Mark all as read
                        </button>
                    </div>
                    <div class="overflow-y-auto max-h-96">
                        <template v-if="notifications.length > 0">
                            <a v-for="notification in notifications" :key="notification.id" href="#"
                                class="block px-4 py-3 transition-colors duration-150 hover:bg-gray-50" :class="{
                                    'bg-indigo-50/50 border-l-4 border-l-indigo-500': !notification.read_at,
                                    'border-l-4 border-l-transparent': notification.read_at
                                }" @click.prevent="markAsRead(notification)">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="w-3 h-3 rounded-full"
                                            :class="notification.read_at ? 'bg-gray-300' : 'bg-indigo-500'"></div>
                                    </div>
                                    <div class="flex-1 ml-3">
                                        <div class="mb-1 text-sm font-medium text-gray-900">
                                            {{ parseData(notification).title || 'Notification' }}
                                        </div>
                                        <div class="text-sm text-gray-600 mb-1.5">
                                            {{ parseData(notification).body }}
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ formatDate(notification.created_at) }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </template>
                        <div v-else class="px-4 py-6 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <h4 class="mt-2 text-sm font-medium text-gray-900">No notifications</h4>
                            <p class="mt-1 text-sm text-gray-500">We'll notify you when something arrives.</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 text-center border-t border-gray-100">
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            View all notifications
                        </a>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'
    import { router } from '@inertiajs/vue3'

    const notifications = ref([])
    const unreadCount = ref(0)
    const showingNotificationsDropdown = ref(false)

    const toggleNotifications = () => {
        showingNotificationsDropdown.value = !showingNotificationsDropdown.value
        if (showingNotificationsDropdown.value && unreadCount.value > 0) {
            // Optionally mark all as read when dropdown is opened
            // markAllAsRead()
        }
    }

    const fetchNotifications = async () => {
        try {
            const res = await axios.get('/notifications')
            notifications.value = res.data.notifications
            unreadCount.value = res.data.unread_count
        } catch (error) {
            console.error('Error fetching notifications:', error)
        }
    }

    const markAsRead = async (notification) => {
        if (!notification.read_at) {
            try {
                await axios.post(`/notifications/${notification.id}/read`)
                notification.read_at = new Date()
                unreadCount.value = Math.max(unreadCount.value - 1, 0)
            } catch (error) {
                console.error('Failed to mark as read:', error)
            }
        }
        // Optionally navigate to the notification target
        if (notification.data?.color) {
            router.visit(route('tickets.show', notification.data.color))
        }
    }

    const markAllAsRead = async () => {
        try {
            await axios.post('/notifications/mark-all-read')
            notifications.value.forEach(n => n.read_at = n.read_at || new Date())
            unreadCount.value = 0
        } catch (error) {
            console.error('Failed to mark all as read:', error)
        }
    }

    const formatDate = (timestamp) => {
        const now = new Date()
        const date = new Date(timestamp)
        const diffInSeconds = Math.floor((now - date) / 1000)

        if (diffInSeconds < 60) return 'Just now'
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`

        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
    }

    const parseData = (notification) => {
        try {
            return typeof notification.data === 'string'
                ? JSON.parse(notification.data)
                : notification.data
        } catch (e) {
            return { title: 'Notification', body: 'Could not parse this notification.' }
        }
    }

    onMounted(() => {
        fetchNotifications()
        // Optional: Poll for new notifications every 60 seconds
        //setInterval(fetchNotifications, 30000)
    })
</script>