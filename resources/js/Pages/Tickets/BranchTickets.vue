<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import TicketTabs from '@/Components/TicketTabs.vue';

    const props = defineProps({
        tickets: {
            type: Array,
            default: () => []
        },
        statuses: {
            type: Array,
            default: () => []
        },
        filters: {
            type: Object,
            default: () => ({})
        },
        total_tickets: {
            type: Number,
            default: 0
        }
    });

    // Priority colors
    const priorityColors = {
        low: 'bg-green-100 text-green-800',
        medium: 'bg-yellow-100 text-yellow-800',
        high: 'bg-red-100 text-red-800',
        urgent: 'bg-purple-100 text-purple-800'
    };

    // Dynamic status color class generator
    const getStatusColorClass = (status) => {
        const color = status?.color || 'gray';
        const colorMap = {
            gray: 'bg-gray-100 text-gray-800',
            blue: 'bg-blue-100 text-blue-800',
            yellow: 'bg-yellow-100 text-yellow-800',
            green: 'bg-green-100 text-green-800',
            red: 'bg-red-100 text-red-800',
            purple: 'bg-purple-100 text-purple-800',
            indigo: 'bg-indigo-100 text-indigo-800'
        };
        return colorMap[color] || 'bg-gray-100 text-gray-800';
    };

    // Filter by status
    const filterByStatus = (statusId) => {
        router.get(route('tickets.branch-tickets'), { status: statusId }, {
            preserveState: true,
            replace: true,
        });
    };

    // Clear all filters
    const clearFilters = () => {
        router.get(route('tickets.branch-tickets'), {}, {
            preserveState: true,
            replace: true,
        });
    };

    // Safe way to find active status name
    const activeStatusName = computed(() => {
        if (!props.filters?.status) return null;
        const foundStatus = props.statuses.find(s => s.id == props.filters.status);
        return foundStatus?.name || null;
    });



    // Status color class for cards
    const getStatusCardColor = (status) => {
        const color = status?.color || 'gray';
        return {
            border: `border-${color}-200`,
            bg: `bg-${color}-50`,
            text: `text-${color}-600`,
            hover: `hover:border-${color}-400`
        };
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
                <TicketTabs active="branch-tickets" :has_bm_role="true"></TicketTabs>
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-xl">
                    <div class="p-6 sm:p-8">
                        <!-- Status cards section -->
                        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-5">

                            <div v-for="status in statuses" :key="status.id" @click="filterByStatus(status.id)"
                                class="p-4 transition-all duration-200 border-2 rounded-lg cursor-pointer" :class="[
                                                                getStatusCardColor(status).border,
                                                                getStatusCardColor(status).bg,
                                                                { 
                                                                    'border-indigo-500 ring-1 ring-indigo-200': 
                                                                        (filters?.status == status.id) || 
                                                                        (!filters?.status && status.name.toLowerCase() === 'open')
                                                                },
                                                                getStatusCardColor(status).hover
                                                            ]">
                                <div class="text-sm font-medium" :class="getStatusCardColor(status).text">
                                    {{ status.name }}
                                </div>
                                <div class="mt-1 text-2xl font-bold" :class="getStatusCardColor(status).text">
                                    {{ status.tickets_count || 0 }}
                                </div>
                            </div>
                            <div @click="filterByStatus('all')"
                                class="p-4 transition-all duration-200 border-2 rounded-lg cursor-pointer hover:border-indigo-300"
                                :class="[
                                                                'border-indigo-100 bg-indigo-50',
                                                                { 'border-indigo-500 ring-1 ring-indigo-200': filters?.status=='all' }
                                                            ]">
                                <div class="text-sm font-medium text-indigo-600">Total Tickets</div>
                                <div class="mt-1 text-2xl font-bold text-indigo-800">
                                    {{ total_tickets }}
                                </div>
                            </div>
                        </div>

                        <!-- Active filter indicator -->
                        <div v-if="filters?.status && activeStatusName" class="flex items-center mb-4">
                            <span class="text-sm text-gray-600">
                                Showing tickets with status:
                                <span class="font-semibold">
                                    {{ activeStatusName }}
                                </span>
                            </span>
                            <button @click="clearFilters" class="ml-2 text-sm text-indigo-600 hover:text-indigo-800">
                                (Clear filter)
                            </button>
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
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Creator
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
                                                    {{ ticket.support_topic?.support_unit?.department?.name || 'N/A' }}
                                                    <br>
                                                    {{ ticket.support_topic?.support_unit?.name || 'N/A' }} <br>
                                                    {{ ticket.support_topic?.name || 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    :class="priorityColors[ticket.priority] || 'bg-gray-100 text-gray-800'"
                                                    class="inline-flex px-3 py-1 text-xs font-semibold leading-5 capitalize rounded-full">
                                                    {{ ticket.priority }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6- whitespace-nowrap">
                                                <span :class="getStatusColorClass(ticket.status)"
                                                    class="inline-flex px-3 py-1 text-xs font-semibold leading-5 capitalize rounded-full">
                                                    {{ ticket.status.name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ ticket.formatted_updated_at }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ ticket.creator.name }}
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
                                            <td colspan="6" class="px-6 py-12 text-center">
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
                                                    <!-- <div class="mt-6">
                                                        <Link :href="route('tickets.create')"
                                                            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                        Create New Ticket
                                                        </Link>
                                                    </div> -->
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>