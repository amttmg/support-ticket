<script setup>
    import { ref } from 'vue';
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import NavLink from '@/Components/NavLink.vue';
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
    import { Link, usePage } from '@inertiajs/vue3';

    const showingNavigationDropdown = ref(false);
    const appName = "Support Center"; // Replace with your app name or use a prop if needed
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <div class="relative flex flex-col min-h-screen">
            <!-- Header - Matching Welcome.vue style -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <Link :href="route('tickets.index')">
                        <svg class="w-auto h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        </Link>
                        <span class="ml-2 text-xl font-bold text-gray-900">{{ appName }}</span>
                    </div>

                    <!-- Navigation for authenticated users -->
                    <nav class="flex items-center space-x-8">
                        <!-- Main Navigation Links -->
                        <div class="hidden sm:flex sm:space-x-8">
                            <!-- <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink> -->
                            <NavLink :href="route('tickets.index')" :active="route().current('tickets.index')">
                                Dashboard
                            </NavLink>
                            <NavLink :href="route('tickets.create')" :active="route().current('tickets.create')">
                                Create Ticket
                            </NavLink>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative ml-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-100 focus:outline-none">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center -me-2 sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                                hidden: showingNavigationDropdown,
                                                'inline-flex': !showingNavigationDropdown,
                                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                                hidden: !showingNavigationDropdown,
                                                'inline-flex': showingNavigationDropdown,
                                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </nav>
                </div>
            </header>

            <!-- Mobile Navigation Menu -->
            <div :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }" class="bg-white shadow-md sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('tickets.index')" :active="route().current('tickets.index')">
                        My Tickets
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('tickets.create')" :active="route().current('tickets.create')">
                        Create Ticket
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ $page.props.auth.user.email }}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-grow">
                <!-- Page Heading -->
                <!-- <header class="bg-white shadow" v-if="$slots.header">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header> -->

                <!-- Page Content -->
                <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>

            <!-- Footer - Matching Welcome.vue style -->
            <footer class="py-8 mt-12 bg-white">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <p class="text-sm text-center text-gray-500">&copy; 2025 IT Department Support Center. All rights
                        reserved.</p>
                </div>
            </footer>
        </div>
    </div>
</template>