<script setup>
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue'; // You'll need this component
    import { Head, Link, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        branches: Array,
        auto_detect_ip: Boolean,
    });

    const form = useForm({
        name: '',
        emp_no: '',
        mobile_number: '',
        branch_id: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = () => {
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <form @submit.prevent="submit">
            <div class="flex gap-4">
                <div class="flex-1">
                    <InputLabel for="name" value="Full Name" />

                    <TextInput id="name" type="text" class="block w-full mt-1" v-model="form.name" required autofocus
                        autocomplete="name" />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div class="flex-1">
                    <InputLabel for="employee_number" value="Employee Number" />

                    <TextInput id="employee_number" type="text" class="block w-full mt-1" v-model="form.emp_no" required
                        autocomplete="off" />

                    <InputError class="mt-2" :message="form.errors.emp_no" />
                </div>
            </div>

            <div class="flex gap-4 mt-4">
                <div class="flex-1">
                    <InputLabel for="mobile_number" value="Mobile Number" />

                    <TextInput id="mobile_number" type="text" class="block w-full mt-1" v-model="form.mobile_number"
                        required autocomplete="tel" />

                    <InputError class="mt-2" :message="form.errors.mobile_number" />
                </div>
                <div class="flex-1">
                    <InputLabel for="email" value="Email" />

                    <TextInput id="email" type="email" class="block w-full mt-1" v-model="form.email" required
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div class="mt-4" v-if="!auto_detect_ip">
                <InputLabel for="branch_id" value="Branch" />

                <SelectInput id="branch_id" class="block w-full mt-1" v-model="form.branch_id" required>
                    <option value="" disabled>Select Branch</option>
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                        {{ branch.code }} {{ branch.name }}
                    </option>
                </SelectInput>

                <InputError class="mt-2" :message="form.errors.branch_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="block w-full mt-1" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput id="password_confirmation" type="password" class="block w-full mt-1"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                    class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>