<script setup>
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex flex-col items-center justify-center min-h-screen p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 sm:p-6">
        <!-- Logo/Header Section -->
        <div class="flex flex-col items-center mb-8 space-y-4">
            <Link href="/" class="flex items-center group">
            <svg class="w-auto h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <span class="ml-3 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                Support Center
            </span>
            </Link>

        </div>

        <!-- Content Card -->
        <div
            class="w-full max-w-md overflow-hidden transition-all duration-300 bg-white shadow-xl dark:bg-gray-800 rounded-xl hover:shadow-2xl">
            <div class="p-0.5 bg-gradient-to-r from-blue-500 to-red-100"></div>
            <div class="px-8 py-6">
                <slot />
            </div>
        </div>

        <!-- Footer with Register Link -->
        <div class="mt-8 space-y-2 text-center">

            <p class="text-xs text-gray-400 dark:text-gray-500">
                © 2023 SupportCenter. All rights reserved.
            </p>
        </div>
    </div>
</template>