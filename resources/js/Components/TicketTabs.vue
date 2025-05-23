<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import { defineProps } from 'vue';

    const props = defineProps({
        active: String,
    });
</script>

<template>
    <div class="flex mb-5 border-b border-gray-200">
        <!-- My Tickets Tab -->
        <Link :href="route('tickets.index')" class="flex items-center px-4 py-2 text-sm font-medium" :class="{
                'text-blue-600 border-b-2 border-blue-500': active === 'my-tickets',
                'text-gray-500 hover:text-gray-700': active !== 'my-tickets'
            }">
        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" :class="{
                    'text-blue-500': active === 'my-tickets',
                    'text-gray-400': active !== 'my-tickets'
                }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        My Tickets
        </Link>

        <!-- Branch Tickets Tab -->
        <Link :href="route('tickets.branch-tickets')" class="flex items-center px-4 py-2 text-sm font-medium" :class="{
                'text-blue-600 border-b-2 border-blue-500': active === 'branch-tickets',
                'text-gray-500 hover:text-gray-700': active !== 'branch-tickets'
            }">
        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" :class="{
                    'text-blue-500': active === 'branch-tickets',
                    'text-gray-400': active !== 'branch-tickets'
                }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 10l9-7 9 7M4 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10" />
        </svg>
        Branch Tickets
        </Link>
    </div>
</template>