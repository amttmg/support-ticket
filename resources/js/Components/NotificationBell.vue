<template>
    <!-- Notifications Dropdown -->
    <div class="relative ml-3">
        <button @click="toggleNotifications"
            class="relative p-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">View notifications</span>
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ unreadCount }}
            </span>
        </button>

        <!-- Notifications dropdown panel -->
        <div v-show="showingNotificationsDropdown"
            class="absolute right-0 z-10 w-64 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1">
                <div class="px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-100">
                    Notifications
                </div>
                <div class="overflow-y-auto max-h-64">
                    <template v-if="notifications.length > 0">
                        <a v-for="notification in notifications" :key="notification.id" href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            :class="{ 'bg-gray-50': !notification.read_at }" @click.prevent="markAsRead(notification)">
                            <div class="flex justify-between">
                                <span>{{ notification.message }}</span>
                            </div>
                            <div class="text-xs text-gray-500">{{ formatDate(notification.created_at) }}</div>
                        </a>
                    </template>
                    <div v-else class="px-4 py-2 text-sm text-gray-500">
                        No new notifications
                    </div>
                </div>
                <div class="px-4 py-2 text-sm font-medium text-indigo-600 border-t border-gray-100">
                    <a href="#">View all notifications</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'

    const notifications = ref([])
    const unreadCount = ref(0)
    const showingNotificationsDropdown = ref(false)

    const toggleNotifications = () => {
        showingNotificationsDropdown.value = !showingNotificationsDropdown.value
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
    }

    const formatDate = (timestamp) => {
        return new Date(timestamp).toLocaleString()
    }

    onMounted(() => {
        fetchNotifications()
    })
</script>