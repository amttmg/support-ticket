<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';

    defineProps({
        ticket: Object,
    });
</script>

<template>

    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ticket Details
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold">{{ ticket.title }}</h3>
                            <div class="flex items-center mt-2 space-x-4">
                                <span :class="{
                                            'bg-green-100 text-green-800': ticket.priority === 'low',
                                            'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                            'bg-red-100 text-red-800': ticket.priority === 'high',
                                        }" class="px-3 py-1 text-sm font-semibold capitalize rounded-full">
                                    {{ ticket.priority }} priority
                                </span>
                                <span class="px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                                    {{ ticket.status.name }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 mb-8 rounded-lg bg-gray-50">
                            <h4 class="mb-2 text-lg font-medium">Description</h4>
                            <p class="whitespace-pre-line">{{ ticket.description }}</p>
                        </div>

                        <div class="flex justify-end">
                            <Link :href="route('tickets.index')"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Back to Tickets
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>