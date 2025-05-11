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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket Details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold">{{ ticket.title }}</h3>
                            <div class="flex items-center mt-2 space-x-4">
                                <span :class="{
                                            'bg-green-100 text-green-800': ticket.priority === 'low',
                                            'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                            'bg-red-100 text-red-800': ticket.priority === 'high',
                                        }" class="px-3 py-1 text-sm font-semibold rounded-full capitalize">
                                    {{ ticket.priority }} priority
                                </span>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Open
                                </span>
                                <span class="text-sm text-gray-500">
                                    Created {{ new Date(ticket.created_at).toLocaleDateString() }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-lg mb-2">Description</h4>
                            <p class="whitespace-pre-line">{{ ticket.description }}</p>
                        </div>

                        <div class="flex justify-end">
                            <Link :href="route('tickets.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Tickets
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>