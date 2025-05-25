<script setup>
    import { Head, Link, usePage, useForm } from '@inertiajs/vue3'
    import { ref } from 'vue';

    defineProps({
        branches: Array, // List of branches
        auto_detect_ip: Boolean, // Whether to auto-detect IP
        canLogin: Boolean,
        canRegister: Boolean,
        errors: Object, // Laravel validation errors
    });

    const appName = usePage().props.appName
    const page = usePage();
    const showAuthModal = ref(false);
    const authMode = ref('login'); // 'login' or 'register'

    // Login Form (using Inertia's useForm)
    const loginForm = useForm({
        email: '',
        password: '',
        remember: false,
    });

    // Registration Form (using Inertia's useForm)
    const registerForm = useForm({
        name: '',
        mobile_number: '',
        branch_id: '', // Assuming branch selection is needed
        email: '',
        password: '',
        password_confirmation: '',
    });

    const openAuthModal = (mode) => {
        authMode.value = mode;
        showAuthModal.value = true;
    };

    const handleCreateTicket = () => {
        if (page.props.auth.user) {
            window.location.href = route('tickets.create');
        } else {
            openAuthModal('login');
        }
    };

    const submitLogin = () => {
        loginForm.post(route('login'), {
            onSuccess: () => {
                showAuthModal.value = false;
                loginForm.reset();
            },
        });
    };

    const submitRegister = () => {
        registerForm.post(route('register'), {
            onSuccess: () => {
                showAuthModal.value = false;
                registerForm.reset();
            },
        });
    };
</script>

<template>

    <Head :title="appName" />
    <div class="min-h-screen bg-gray-50">
        <div class="relative flex flex-col min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <svg class="w-auto h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">{{ appName }}</span>
                    </div>
                    <nav v-if="canLogin" class="flex justify-end flex-1 -mx-3">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-100">
                        Dashboard
                        </Link>

                        <template v-else>
                            <button type="button" @click="openAuthModal('login')"
                                class="px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-100">
                                Log in
                            </button>

                            <button type="button" v-if="canRegister" @click="openAuthModal('register')"
                                class="px-3 py-2 ml-4 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-100">
                                Register
                            </button>
                        </template>
                    </nav>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow">
                <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white rounded-lg shadow">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="text-center">
                                <h1 class="mb-2 text-3xl font-bold text-gray-900">How can we help you today?</h1>
                                <p class="mb-8 text-lg text-gray-600">Submit a ticket and our support team will get back
                                    to you as soon as possible.</p>
                            </div>

                            <!-- Ticket Form -->
                            <div class="max-w-2xl mx-auto mt-8">
                                <div class="text-center">
                                    <button @click="handleCreateTicket"
                                        class="inline-flex items-center justify-center px-6 py-3 text-lg font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Create a Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Help Section -->
                    <div class="grid grid-cols-1 gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="overflow-hidden bg-white rounded-lg shadow">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-indigo-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <h3 class="text-lg font-medium text-gray-900">Knowledge Base</h3>
                                        <p class="mt-1 text-sm text-gray-500">Browse our documentation and FAQs.</p>
                                        <a href="#"
                                            class="mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">View
                                            articles</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden bg-white rounded-lg shadow">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-indigo-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <h3 class="text-lg font-medium text-gray-900">Community Forum</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get help from our community.</p>
                                        <a href="#"
                                            class="mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">Visit
                                            forum</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden bg-white rounded-lg shadow">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-indigo-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
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
            <footer class="py-8 mt-8 bg-white">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <p class="text-sm text-center text-gray-500">&copy; 2025 IT Department Support Center. All rights
                        reserved.</p>
                </div>
            </footer>
        </div>

        <!-- Auth Modal -->
        <div v-if="showAuthModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75" @click="showAuthModal = false"></div>
                </div>

                <!-- Modal content -->
                <div
                    class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                    <div>
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-indigo-100 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                {{ authMode === 'login' ? 'Sign in to create a ticket' : 'Create an account' }}
                            </h3>

                            <!-- Login Form (Shown when authMode === 'login') -->
                            <form v-if="authMode === 'login'" @submit.prevent="submitLogin">
                                <div class="mt-4">
                                    <input type="email" placeholder="Email" v-model="loginForm.email" required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="errors.email" class="mt-1 text-sm text-left text-red-500">{{ errors.email
                                        }}</div>
                                </div>
                                <div class="mt-4">
                                    <input type="password" placeholder="Password" v-model="loginForm.password" required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="errors.password" class="mt-1 text-sm text-red-500">{{ errors.password }}
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="loginForm.remember"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                    </label>
                                    <Link :href="route('password.request')"
                                        class="text-sm text-indigo-600 hover:text-indigo-500">
                                    Forgot your password?
                                    </Link>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" :disabled="loginForm.processing"
                                        class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span v-if="loginForm.processing">Logging in...</span>
                                        <span v-else>Sign in</span>
                                    </button>
                                    <button v-if="canRegister" @click="authMode = 'register'" type="button"
                                        class="flex justify-center w-full px-4 py-2 mt-2 text-sm font-medium text-indigo-700 bg-indigo-100 border border-transparent rounded-md shadow-sm hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Create new account
                                    </button>
                                </div>
                            </form>

                            <!-- Registration Form (Shown when authMode === 'register') -->
                            <form v-else @submit.prevent="submitRegister">
                                <div class="mt-4">
                                    <input type="text" placeholder="Full Name" v-model="registerForm.name" required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name }}</div>
                                </div>
                                <div class="mt-4">
                                    <input type="email" placeholder="Email" v-model="registerForm.email" required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="errors.email" class="mt-1 text-sm text-red-500">{{ errors.email }}</div>
                                </div>
                                <div class="mt-4">
                                    <input type="text" placeholder="Mobile" v-model="registerForm.mobile_number"
                                        required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="errors.mobile_number" class="mt-1 text-sm text-red-500">{{
                                        errors.mobile_number }}</div>
                                </div>
                                <div class="mt-4" v-if="!auto_detect_ip">
                                    <select v-model="registerForm.branch_id" required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled>Select Branch</option>
                                        <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                                            {{ branch.name }}
                                        </option>
                                    </select>
                                    <div v-if="errors.branch_id" class="mt-1 text-sm text-red-500">{{ errors.branch_id
                                        }}</div>
                                </div>
                                <div class="mt-4">
                                    <input type="password" placeholder="Password" v-model="registerForm.password"
                                        required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="errors.password" class="mt-1 text-sm text-red-500">{{ errors.password }}
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <input type="password" placeholder="Confirm Password"
                                        v-model="registerForm.password_confirmation" required
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div class="mt-6">
                                    <button type="submit" :disabled="registerForm.processing"
                                        class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span v-if="registerForm.processing">Registering...</span>
                                        <span v-else>Register</span>
                                    </button>
                                    <button @click="authMode = 'login'" type="button"
                                        class="flex justify-center w-full px-4 py-2 mt-2 text-sm font-medium text-indigo-700 bg-indigo-100 border border-transparent rounded-md shadow-sm hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Already have an account? Sign in
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>