<script setup>
    import { usePage } from '@inertiajs/vue3';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';

    const user = usePage().props.auth.user;
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Your account's profile information.
            </p>
        </header>

        <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2">
            <div>
                <InputLabel value="Employee Number" />
                <TextInput type="text" class="block w-full mt-1 bg-gray-100 cursor-not-allowed"
                    :modelValue="user.emp_no" readonly disabled />
            </div>

            <div>
                <InputLabel value="Name" />
                <TextInput type="text" class="block w-full mt-1 bg-gray-100 cursor-not-allowed" :modelValue="user.name"
                    readonly disabled />
            </div>

            <div>
                <InputLabel value="Email" />
                <TextInput type="email" class="block w-full mt-1 bg-gray-100 cursor-not-allowed"
                    :modelValue="user.email" readonly disabled />
            </div>

            <div>
                <InputLabel value="Mobile Number" />
                <TextInput type="text" class="block w-full mt-1 bg-gray-100 cursor-not-allowed"
                    :modelValue="user.mobile_number" readonly disabled />
            </div>

            <div>
                <InputLabel value="Branch" />
                <TextInput type="text" class="block w-full mt-1 bg-gray-100 cursor-not-allowed"
                    :modelValue="user.branch?.name" readonly disabled />
            </div>
        </div>
    </section>
</template>