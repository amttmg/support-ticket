<script setup>
    import { Head, Link } from '@inertiajs/vue3';

    defineProps({
        canLogin: {
            type: Boolean,
        },
        canRegister: {
            type: Boolean,
        },
    });
</script>

<template>

    <Head title="Support Center" />
    <div class="min-h-screen bg-gray-50">
        <div class="relative flex min-h-screen flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-8 w-auto text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">Support Center</span>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100">
                        Dashboard
                        </Link>

                        <!-- <template v-else>
                            <Link :href="route('login')"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100">
                            Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 ml-4">
                            Register
                            </Link>
                        </template> -->
                    </nav>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow">
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="text-center">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">How can we help you today?</h1>
                                <p class="text-lg text-gray-600 mb-8">Submit a ticket and our support team will get back
                                    to you as soon as possible.</p>
                            </div>

                            <!-- Ticket Form -->
                            <div class="mt-8 max-w-2xl mx-auto">
                                <div class="text-center">
                                    <button
                                        class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-6 py-3 text-lg font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Create a Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Help Section -->
                    <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">Knowledge Base</h3>
                                        <p class="mt-1 text-sm text-gray-500">Browse our documentation and FAQs.</p>
                                        <a href="#"
                                            class="mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">View
                                            articles</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">Community Forum</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get help from our community.</p>
                                        <a href="#"
                                            class="mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">Visit
                                            forum</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">Live Chat</h3>
                                        <p class="mt-1 text-sm text-gray-500">Chat with our support team.</p>
                                        <a href="#"
                                            class="mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">Start
                                            chat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white py-8 mt-12">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">&copy; 2025 IT Department Support Center. All rights
                        reserved.</p>
                </div>
            </footer>
        </div>
    </div>
</template>