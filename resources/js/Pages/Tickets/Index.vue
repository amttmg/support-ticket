<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import { ref } from 'vue';

    defineProps({
        tickets: Array,
    });

    const statusColors = {
        open: 'bg-blue-100 text-blue-800',
        pending: 'bg-yellow-100 text-yellow-800',
        solved: 'bg-green-100 text-green-800',
        closed: 'bg-gray-100 text-gray-800',
        cancelled: 'bg-red-100 text-red-800'
    };

    const priorityColors = {
        low: 'bg-green-100 text-green-800',
        medium: 'bg-yellow-100 text-yellow-800',
        high: 'bg-red-100 text-red-800',
        urgent: 'bg-purple-100 text-purple-800'
    };
</script>

<template>

    <Head title="My Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    My Support Tickets
                </h2>
                <Link :href="route('tickets.create')"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-white uppercase transition-all duration-150 ease-in-out border border-transparent rounded-lg shadow-md bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 active:from-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                        clip-rule="evenodd" />
                </svg>
                New Ticket
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-xl">
                    <div class="p-6 sm:p-8">
                        <!-- Header with stats -->
                        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-4">
                            <div class="p-4 border border-indigo-100 rounded-lg bg-indigo-50">
                                <div class="text-sm font-medium text-indigo-600">Total Tickets</div>
                                <div class="mt-1 text-2xl font-bold text-indigo-800">{{ tickets.length }}</div>
                            </div>
                            <div class="p-4 border border-blue-100 rounded-lg bg-blue-50">
                                <div class="text-sm font-medium text-blue-600">Open</div>
                                <div class="mt-1 text-2xl font-bold text-blue-800">
                                    {{ tickets.filter(t => t.status === 'open').length }}
                                </div>
                            </div>
                            <div class="p-4 border border-yellow-100 rounded-lg bg-yellow-50">
                                <div class="text-sm font-medium text-yellow-600">Pending</div>
                                <div class="mt-1 text-2xl font-bold text-yellow-800">
                                    {{ tickets.filter(t => t.status === 'pending').length }}
                                </div>
                            </div>
                            <div class="p-4 border border-green-100 rounded-lg bg-green-50">
                                <div class="text-sm font-medium text-green-600">Solved</div>
                                <div class="mt-1 text-2xl font-bold text-green-800">
                                    {{ tickets.filter(t => t.status === 'solved').length }}
                                </div>
                            </div>
                        </div>

                        <!-- Tickets Table -->
                        <div class="overflow-hidden bg-white border border-gray-100 rounded-xl">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Ticket Details
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Department
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Priority
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Last Updated
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <tr v-for="ticket in tickets" :key="ticket.id"
                                            class="transition-colors duration-150 hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-indigo-700 rounded-lg bg-indigo-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ ticket.title }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            #{{ ticket.id }} • {{ new
                                                            Date(ticket.created_at).toLocaleDateString() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-3 py-1 text-xs font-semibold leading-5 capitalize rounded-full">

                                                    {{ ticket.support_topic.support_unit.department.name }} <br>
                                                    {{ ticket.support_topic.support_unit.name }} <br>
                                                    {{ ticket.support_topic.name }}

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    :class="priorityColors[ticket.priority] || 'bg-gray-100 text-gray-800'"
                                                    class="inline-flex px-3 py-1 text-xs font-semibold leading-5 capitalize rounded-full">
                                                    {{ ticket.priority }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    :class="statusColors[ticket.status] || 'bg-gray-100 text-gray-800'"
                                                    class="inline-flex px-3 py-1 text-xs font-semibold leading-5 capitalize rounded-full">
                                                    {{ ticket.status.name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ new Date(ticket.updated_at).toLocaleString() }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                <div class="flex justify-end space-x-3">
                                                    <Link :href="route('tickets.show', ticket.id)"
                                                        class="flex items-center text-indigo-600 hover:text-indigo-900 group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="tickets.length === 0">
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <h3 class="mt-4 text-lg font-medium text-gray-700">No tickets found
                                                    </h3>
                                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new
                                                        support ticket</p>
                                                    <div class="mt-6">
                                                        <Link :href="route('tickets.create')"
                                                            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                        Create New Ticket
                                                        </Link>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination would go here if needed -->
                        <!-- <div class="flex items-center justify-between mt-6">
                                ...
                            </div> -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>