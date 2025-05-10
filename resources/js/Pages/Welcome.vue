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

                        <template v-else>
                            <Link :href="route('login')"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100">
                            Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 ml-4">
                            Register
                            </Link>
                        </template>
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
                                <form class="space-y-6">
                                    <div>
                                        <label for="subject"
                                            class="block text-sm font-medium text-gray-700">Subject</label>
                                        <input type="text" id="subject" name="subject"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-4 border"
                                            placeholder="Briefly describe your issue">
                                    </div>

                                    <div>
                                        <label for="department"
                                            class="block text-sm font-medium text-gray-700">Department</label>
                                        <select id="department" name="department"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-3 px-4 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                            <option>Technical Support</option>
                                            <option>Billing</option>
                                            <option>Sales</option>
                                            <option>General Inquiry</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="priority"
                                            class="block text-sm font-medium text-gray-700">Priority</label>
                                        <select id="priority" name="priority"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-3 px-4 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                            <option>Low</option>
                                            <option>Medium</option>
                                            <option>High</option>
                                            <option>Critical</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="description" name="description" rows="6"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-4 border"
                                            placeholder="Please describe your issue in detail"></textarea>
                                    </div>

                                    <div>
                                        <label for="attachments"
                                            class="block text-sm font-medium text-gray-700">Attachments</label>
                                        <div
                                            class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none hover:text-indigo-500">
                                                        <span>Upload files</span>
                                                        <input id="file-upload" name="file-upload" type="file"
                                                            class="sr-only" multiple>
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-3 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            Submit Ticket
                                        </button>
                                    </div>
                                </form>
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
                    <p class="text-center text-sm text-gray-500">&copy; 2023 Support Center. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>
</template>