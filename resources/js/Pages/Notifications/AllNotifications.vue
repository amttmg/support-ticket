<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';

    defineProps({
        notifications: Array,
        unread_count: Number
    })

    const markAllAsRead = async () => {
        try {
            await axios.post('/notifications/mark-all-read')
            router.reload() // Refresh the page after marking all as read
        } catch (error) {
            console.error('Failed to mark all as read:', error)
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
</script>

<template>

    <Head title="Notifications" />

    <AuthenticatedLayout>
        <div class="max-w-2xl p-4 mx-auto sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
                <button @click="markAllAsRead" v-if="unread_count>0"
                    class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :class="{'opacity-50 cursor-not-allowed': notifications.length === 0}">
                    Mark All as Read
                </button>
            </div>

            <div v-if="notifications.length === 0"
                class="flex flex-col items-center justify-center p-8 text-center bg-white rounded-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No notifications yet</h3>
                <p class="mt-1 text-sm text-gray-500">We'll notify you when something arrives.</p>
            </div>

            <div v-else class="space-y-3">
                <div v-for="notification in notifications" :key="notification.id"
                    class="p-4 mb-4 transition-all bg-white border rounded-lg shadow-sm hover:shadow-md" :class="{
                            'border-l-4 border-indigo-500': !notification.read_at,
                            'opacity-90': notification.read_at
                        }">
                    <div class="flex items-start cursor-pointer" @click="markAsRead(notification)">
                        <div class="flex-shrink-0 pt-0.5">
                            <div
                                class="flex items-center justify-center w-8 h-8 text-indigo-600 bg-indigo-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                {{ notification.data.title }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ notification.data.message || 'New notification' }}
                            </p>
                            <div class="flex items-center mt-1 text-xs text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ notification.created_at }}
                            </div>
                        </div>
                        <div v-if="!notification.read_at" class="flex-shrink-0 ml-2">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                New
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>