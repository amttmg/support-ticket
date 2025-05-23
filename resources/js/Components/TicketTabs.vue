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
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" :class="{
                    'text-blue-500': active === 'my-tickets',
                    'text-gray-400': active !== 'my-tickets'
                }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
        </svg>
        My Tickets
        </Link>

        <!-- Branch Tickets Tab -->
        <Link :href="route('tickets.branch-tickets')" class="flex items-center px-4 py-2 text-sm font-medium" :class="{
                'text-blue-600 border-b-2 border-blue-500': active === 'branch-tickets',
                'text-gray-500 hover:text-gray-700': active !== 'branch-tickets'
            }">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" :class="{
                    'text-blue-500': active === 'branch-tickets',
                    'text-gray-400': active !== 'branch-tickets'
                }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        Branch Tickets
        </Link>
    </div>
</template>