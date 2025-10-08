<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, useForm, router } from '@inertiajs/vue3';
    import Comment from '@/Components/Comment.vue';
    import Swal from '@/Helpers/sweetalert'

    import { ref } from 'vue'

    const showModal = ref(false)
    const selectedFile = ref(null)

    const openFile = (file) => {
        selectedFile.value = file
        showModal.value = true
    }

    const closeModal = () => {
        showModal.value = false
        selectedFile.value = null
    }

    const isImage = (path) => {
        return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(path)
    }

    const props = defineProps({
        ticket: Object,
        comments: Object,
    });

    const form = useForm({
        body: '',
        parent_id: null,
    });

    const submitComment = () => {
        form.post(route('tickets.comments.store', { ticket: props.ticket.id }), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    };
    const confirmReopen = async (ticket) => {
        const result = await Swal.confirm({
            title: 'Re-open Ticket?',
            text: 'This will change the ticket status back to open',
            icon: 'question',
            confirmColor: '#10B981', // green
            confirmText: 'Re-open'
        })

        if (result.isConfirmed) {
            router.put(route('tickets.reopen', ticket.id), {
                onSuccess: () => {
                    Swal.success('Ticket has been re-opened!')
                },
                onError: () => {
                    Swal.error('Failed to re-open ticket')
                }
            })
        }
    }

    const confirmClose = async (ticket) => {
        const result = await Swal.confirm({
            title: 'Close Ticket?',
            text: 'This will permanently close the ticket',
            icon: 'warning',
            confirmColor: '#EF4444', // red
            confirmText: 'Close'
        })

        if (result.isConfirmed) {
            router.put(route('tickets.close', ticket.id), {
                onSuccess: () => {
                    Swal.success('Ticket has been closed!')
                },
                onError: () => {
                    Swal.error('Failed to close ticket')
                }
            })
        }
    }
</script>

<template>

    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ticket Information
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4">
                    <Link :href="route('tickets.index')" class="inline-flex items-center text-blue-600 hover:underline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                    </Link>
                </div>

                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Main Content (Left Side) -->
                    <div class="flex-1 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold">{{ ticket.title }}</h3>
                                <p class="text-sm text-gray-500">Ticket ID: #{{ ticket.id }}</p>

                            </div>

                            <div class="p-4 mb-8 rounded-lg bg-gray-50">
                                <h4 class="mb-2 text-lg font-medium">Description</h4>
                                <p class="whitespace-pre-line">{{ ticket.description }}</p>
                            </div>

                            <!-- ✅ Files Section -->
                            <!-- ✅ Files Section -->
                            <div v-if="ticket.files && ticket.files.length" class="p-4 mb-8 rounded-lg bg-gray-50">
                                <h4 class="mb-3 text-lg font-medium">Attachments</h4>

                                <ul class="space-y-2">
                                    <li v-for="file in ticket.files" :key="file.id"
                                        class="flex items-center justify-between p-2 border rounded-md">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l7.778-7.778a4 4 0 00-5.656-5.656L4.93 11.07a6 6 0 108.485 8.485L19 14" />
                                            </svg>
                                            <span class="text-sm text-gray-700 truncate max-w-[200px]">
                                                {{ file.original_name.split('/').pop() }}
                                            </span>
                                        </div>

                                        <!-- ✅ View Button -->
                                        <button @click="openFile(file)"
                                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                            View
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- ✅ Popup Modal -->
                            <div v-if="showModal"
                                class="fixed inset-0 z-50 flex items-center justify-center transition-all bg-black bg-opacity-50">
                                <div
                                    class="relative bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 max-h-[90vh] overflow-auto">
                                    <!-- Close button -->
                                    <button @click="closeModal"
                                        class="absolute text-2xl font-bold text-gray-600 top-2 right-2 hover:text-red-500">&times;</button>

                                    <div class="p-4">
                                        <h4 class="mb-4 text-lg font-medium">{{
                                            selectedFile?.original_name.split('/').pop()
                                            }}</h4>

                                        <!-- Preview (image or iframe) -->
                                        <div v-if="isImage(selectedFile.file_path)" class="flex justify-center">
                                            <img :src="`/storage/${selectedFile.file_path}`" alt="Preview"
                                                class="max-w-full max-h-[80vh] rounded">
                                        </div>
                                        <div v-else class="w-full h-[80vh]">
                                            <iframe :src="`/storage/${selectedFile.file_path}`"
                                                class="w-full h-full rounded" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Comments Section -->
                            <div class="mt-8">
                                <h4 class="mb-4 text-lg font-medium">Comments</h4>

                                <!-- Comments List -->
                                <div v-if="comments && comments.length" class="space-y-4">
                                    <Comment v-for="comment in comments" :key="comment.id" :comment="comment"
                                        :ticket-id="ticket.id" />
                                </div>

                                <!-- Comment Form -->
                                <form @submit.prevent="submitComment" class="mt-4" v-if="ticket.status.name!='Closed'">
                                    <textarea v-model="form.body"
                                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        rows="3" placeholder="Add a comment..." required></textarea>
                                    <div class="flex justify-end mt-2">
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            :disabled="form.processing">
                                            Post Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar (Right Side) -->
                    <div class="w-full lg:w-80 xl:w-96">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="mb-4 text-lg font-medium">Ticket Information</h3>

                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Created</h4>
                                        <span
                                            class="inline-block px-3 py-1 mt-1 text-sm font-semibold text-purple-800 bg-purple-100 rounded-full">
                                            {{ $filters.timeAgo(ticket.created_at) }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Last Updated</h4>
                                        <span
                                            class="inline-block px-3 py-1 mt-1 text-sm font-semibold text-orange-800 bg-orange-100 rounded-full">
                                            {{ $filters.timeAgo(ticket.updated_at) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Priority</h4>
                                        <span :class="{
                                                                                    'bg-green-100 text-green-800': ticket.priority === 'low',
                                                                                    'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                                                                    'bg-red-100 text-red-800': ticket.priority === 'high',
                                                                                }"
                                            class="px-3 py-1 text-sm font-semibold capitalize rounded-full">
                                            {{ ticket.priority }}
                                        </span>

                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                        <span
                                            class="px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                                            {{ ticket.status.name }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Assigned To</h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <template v-if="ticket.agents && ticket.agents.length">
                                                <div v-for="agent in ticket.agents" :key="agent.id"
                                                    class="flex items-center gap-2">
                                                    <img :src="agent.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(agent.name)"
                                                        alt="Avatar" class="object-cover border rounded-full w-7 h-7" />
                                                    <span class="text-sm text-gray-900">{{ agent.name }}</span>
                                                </div>
                                            </template>
                                            <span v-else class="text-sm text-gray-500">Unassigned</span>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Created By</h4>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ ticket.creator?.name }}</p>
                                    </div>
                                    <div v-if="ticket.status.name.toLowerCase() === 'resolved'">
                                        <h4 class="text-sm font-medium text-gray-500">Action</h4>
                                        <div class="flex space-x-2">

                                            <!-- Re-open Button -->
                                            <!-- Re-open Button -->
                                            <button @click="confirmReopen(ticket)"
                                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white transition bg-green-600 rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                                                title="Re-open Ticket">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 17v-2a4 4 0 014-4h3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 7h3v3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M17 17a5 5 0 10-5-5" />
                                                </svg>
                                                Re-open
                                            </button>

                                            <!-- Close Button -->
                                            <button @click="confirmClose(ticket)"
                                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white transition bg-red-600 rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                                title="Close Ticket">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Close
                                            </button>
                                        </div>
                                    </div>

                                    <!-- History Section -->
                                    <div v-if="ticket.activities?.length" class="mt-6">
                                        <h4 class="mb-2 text-sm font-medium text-gray-500">History</h4>
                                        <div class="space-y-2">
                                            <div v-for="activity in ticket.activities" :key="activity.id"
                                                class="flex gap-3 p-2 border rounded bg-gray-50">
                                                <!-- Avatar -->
                                                <img :src="activity.causer?.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(activity.causer?.name || 'System')}`"
                                                    alt="Avatar" class="object-cover w-8 h-8 border rounded-full" />

                                                <!-- Details -->
                                                <div class="flex-1 text-sm text-gray-900">
                                                    <strong>{{ activity.causer?.name || 'System' }}:</strong>
                                                    <span class="text-gray-600">
                                                        <!-- Check if status changed -->
                                                        <template
                                                            v-if="activity.properties?.attributes?.['status.name'] && activity.properties?.old?.['status.name']">
                                                            Changed status to <strong class="capitalize">{{
                                                                activity.properties.attributes['status.name']
                                                                }}</strong>
                                                        </template>
                                                        <template v-else>
                                                            {{ activity.description }}
                                                        </template>
                                                    </span>

                                                    <!-- Show old → new status -->
                                                    <div v-if="activity.properties?.old?.['status.name'] && activity.properties?.attributes?.['status.name']"
                                                        class="mt-1 text-xs text-gray-700">
                                                        <span class="text-red-500">{{
                                                            activity.properties.old['status.name'] }}</span>
                                                        →
                                                        <span class="text-green-600">{{
                                                            activity.properties.attributes['status.name'] }}</span>
                                                    </div>

                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{ new Date(activity.created_at).toLocaleString() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>