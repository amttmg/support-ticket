<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import TextArea from '@/Components/TextArea.vue';
    import SelectInput from '@/Components/SelectInput.vue';

    const form = useForm({
        title: '',
        description: '',
        priority: 'medium',
    });

    const priorities = [
        { value: 'low', label: 'Low' },
        { value: 'medium', label: 'Medium' },
        { value: 'high', label: 'High' },
    ];

    const submit = () => {
        form.post(route('tickets.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                // Optionally show success notification
            },
            onError: () => {
                // Focus on first error field if needed
            }
        });
    };
</script>

<template>

    <Head title="Create Support Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                    Create New Support Ticket
                </h2>
                <Link :href="route('tickets.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
                Back to Tickets
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Title Field -->
                            <div>
                                <InputLabel for="title" value="Ticket Title *" />
                                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title"
                                    required autofocus placeholder="Briefly describe your issue" />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <!-- Description Field -->
                            <div>
                                <InputLabel for="description" value="Description *" />
                                <TextArea id="description" class="mt-1 block w-full" v-model="form.description"
                                    :rows="6" :required="true"
                                    placeholder="Please provide detailed information about your issue..." />
                                <p class="mt-1 text-sm text-gray-500">
                                    Be as specific as possible. Include error messages, steps to reproduce, etc.
                                </p>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <!-- Priority Field -->
                            <div>
                                <InputLabel for="priority" value="Priority *" />
                                <SelectInput id="priority" class="mt-1 block w-full" v-model="form.priority"
                                    :required="true">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </SelectInput>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <p v-if="form.priority === 'high'" class="text-red-600">
                                        <strong>High Priority:</strong> Critical issue affecting core functionality
                                    </p>
                                    <p v-else-if="form.priority === 'medium'" class="text-yellow-600">
                                        <strong>Medium Priority:</strong> Important issue but workaround exists
                                    </p>
                                    <p v-else class="text-green-600">
                                        <strong>Low Priority:</strong> Minor issue or general question
                                    </p>
                                </div>
                                <InputError class="mt-2" :message="form.errors.priority" />
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                                <Link :href="route('tickets.index')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                                </Link>

                                <PrimaryButton :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing">
                                    <span v-if="form.processing">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span v-else>
                                        Submit Ticket
                                    </span>
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>