<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import { ref, computed } from 'vue';

    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import TextArea from '@/Components/TextArea.vue';
    import SelectInput from '@/Components/SelectInput.vue';

    const selectedDepartment = ref(null);
    const selectedUnit = ref(null);
    const selectedTopic = ref(null);

    const form = useForm({
        title: '',
        description: '',
        priority: 'medium',
        department: '',
        unit: '',
        topic: '',
    });

    const departments = [
        { id: 'it', name: 'IT Department', icon: '💻' },
        { id: 'digital', name: 'Digital Banking', icon: '🏦' },
        { id: 'psd', name: 'Payment and Settlement', icon: '💰' },
    ];

    const departmentUnits = {
        it: [
            { id: 'infrastructure', name: 'Infrastructure Team', icon: '🖥️' },
            { id: 'development', name: 'Development Team', icon: '👨‍💻' },
            { id: 'support', name: 'Support Team', icon: '🛠️' },
        ],
        digital: [
            { id: 'mobile', name: 'Mobile Banking', icon: '📱' },
            { id: 'web', name: 'Web Banking', icon: '🌐' },
            { id: 'cards', name: 'Digital Cards', icon: '💳' },
        ],
        psd: [
            { id: 'domestic', name: 'Domestic Payments', icon: '🏠' },
            { id: 'international', name: 'International Payments', icon: '✈️' },
        ],
    };

    const unitTopics = {
        infrastructure: [
            { id: 'hardware', name: 'Hardware Issue', icon: '💽' },
            { id: 'network', name: 'Network Problem', icon: '📶' },
            { id: 'server', name: 'Server Down', icon: '🖥️' },
        ],
        development: [
            { id: 'bug', name: 'Bug Report', icon: '🐛' },
            { id: 'feature', name: 'Feature Request', icon: '✨' },
            { id: 'api', name: 'API Issue', icon: '🔌' },
        ],
        support: [
            { id: 'account', name: 'Account Problem', icon: '👤' },
            { id: 'access', name: 'Access Issue', icon: '🔑' },
            { id: 'other', name: 'Other IT Support', icon: '❓' },
        ],
        mobile: [
            { id: 'login', name: 'Login Problem', icon: '🔐' },
            { id: 'transfer', name: 'Transfer Issue', icon: '💸' },
            { id: 'app-crash', name: 'App Crashing', icon: '💥' },
        ],
        web: [
            { id: 'authentication', name: 'Authentication Issue', icon: '🔒' },
            { id: 'ui', name: 'UI Problem', icon: '🎨' },
            { id: 'performance', name: 'Performance Issue', icon: '⚡' },
        ],
        cards: [
            { id: 'virtual', name: 'Virtual Card', icon: '💳' },
            { id: 'digital-wallet', name: 'Digital Wallet', icon: '📲' },
        ],
        domestic: [
            { id: 'transfer', name: 'Domestic Transfer', icon: '🏦' },
            { id: 'bill', name: 'Bill Payment', icon: '🧾' },
        ],
        international: [
            { id: 'swift', name: 'SWIFT Transfer', icon: '🌍' },
            { id: 'fx', name: 'Foreign Exchange', icon: '💱' },
        ],
    };

    const priorities = [
        { value: 'low', label: 'Low', color: 'text-green-600' },
        { value: 'medium', label: 'Medium', color: 'text-yellow-600' },
        { value: 'high', label: 'High', color: 'text-red-600' },
    ];

    const units = computed(() => selectedDepartment.value ? departmentUnits[selectedDepartment.value] : []);
    const topics = computed(() => selectedUnit.value ? unitTopics[selectedUnit.value] : []);

    const selectedDepartmentName = computed(() => {
        const dept = departments.find(d => d.id === selectedDepartment.value);
        return dept ? dept.name : '';
    });

    const selectedUnitName = computed(() => {
        const unit = units.value.find(u => u.id === selectedUnit.value);
        return unit ? unit.name : '';
    });

    const selectedTopicName = computed(() => {
        const topic = topics.value.find(t => t.id === selectedTopic.value);
        return topic ? topic.name : '';
    });

    const goBackToDepartment = () => {
        selectedDepartment.value = null;
        selectedUnit.value = null;
        selectedTopic.value = null;
    };

    const goBackToUnits = () => {
        selectedUnit.value = null;
        selectedTopic.value = null;
    };

    const goBackToTopics = () => {
        selectedTopic.value = null;
    };

    const submit = () => {
        form.department = selectedDepartment.value;
        form.unit = selectedUnit.value;
        form.topic = selectedTopic.value;

        form.post(route('tickets.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                selectedDepartment.value = null;
                selectedUnit.value = null;
                selectedTopic.value = null;
            }
        });
    };
</script>

<template>

    <Head title="Create Support Ticket" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="w-full max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <div class="p-8 bg-white border-b border-gray-200">
                        <!-- Header -->
                        <div class="mb-8 text-center">
                            <h1 class="text-3xl font-bold text-gray-900">Create Support Ticket</h1>
                            <p class="mt-2 text-gray-600">Get help from our support team</p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="flex items-center justify-center mb-10">
                            <div class="flex items-center">
                                <!-- Step 1 -->
                                <div
                                    :class="`flex items-center justify-center w-10 h-10 rounded-full ${!selectedDepartment ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800'} font-semibold`">
                                    1
                                </div>
                                <div class="w-16 h-1 mx-2 bg-blue-200"></div>

                                <!-- Step 2 -->
                                <div
                                    :class="`flex items-center justify-center w-10 h-10 rounded-full ${selectedDepartment && !selectedUnit ? 'bg-blue-600 text-white' : selectedUnit ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-400'} font-semibold`">
                                    2
                                </div>
                                <div class="w-16 h-1 mx-2 bg-blue-200"></div>

                                <!-- Step 3 -->
                                <div
                                    :class="`flex items-center justify-center w-10 h-10 rounded-full ${selectedUnit && !selectedTopic ? 'bg-blue-600 text-white' : selectedTopic ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-400'} font-semibold`">
                                    3
                                </div>
                                <div class="w-16 h-1 mx-2 bg-blue-200"></div>

                                <!-- Step 4 -->
                                <div
                                    :class="`flex items-center justify-center w-10 h-10 rounded-full ${selectedTopic ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400'} font-semibold`">
                                    4
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Select Department -->
                        <div v-if="!selectedDepartment" class="space-y-6">
                            <h2 class="text-xl font-semibold text-center text-gray-800">Which department can help you?
                            </h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <div v-for="dept in departments" :key="dept.id" @click="selectedDepartment = dept.id"
                                    class="block p-8 transition duration-200 border-2 border-blue-100 shadow-sm cursor-pointer rounded-xl bg-gradient-to-br from-blue-50 to-white hover:border-blue-300 hover:shadow-md">
                                    <div class="text-4xl text-center">{{ dept.icon }}</div>
                                    <h3 class="mt-4 text-xl font-semibold text-center text-blue-800">{{ dept.name }}
                                    </h3>
                                    <p class="mt-2 text-sm text-center text-gray-600">Click to select this department
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Select Support Unit -->
                        <div v-else-if="!selectedUnit" class="space-y-6">
                            <div class="flex items-center justify-between">
                                <button @click="goBackToDepartment"
                                    class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Back to departments
                                </button>
                                <span class="px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                                    {{ selectedDepartmentName }}
                                </span>
                            </div>

                            <h2 class="text-xl font-semibold text-center text-gray-800">Which support unit should handle
                                this?</h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <div v-for="unit in units" :key="unit.id" @click="selectedUnit = unit.id"
                                    class="block p-8 transition duration-200 border-2 border-purple-100 shadow-sm cursor-pointer rounded-xl bg-gradient-to-br from-purple-50 to-white hover:border-purple-300 hover:shadow-md">
                                    <div class="text-4xl text-center">{{ unit.icon }}</div>
                                    <h3 class="mt-4 text-xl font-semibold text-center text-purple-800">{{ unit.name }}
                                    </h3>
                                    <p class="mt-2 text-sm text-center text-gray-600">Click to select this unit</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Select Topic -->
                        <div v-else-if="!selectedTopic" class="space-y-6">
                            <div class="flex items-center justify-between">
                                <button @click="goBackToUnits"
                                    class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Back to units
                                </button>
                                <div class="space-x-2">
                                    <span class="px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                                        {{ selectedDepartmentName }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-sm font-medium text-purple-800 bg-purple-100 rounded-full">
                                        {{ selectedUnitName }}
                                    </span>
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold text-center text-gray-800">What's the specific issue?</h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <div v-for="topic in topics" :key="topic.id" @click="selectedTopic = topic.id"
                                    class="block p-8 transition duration-200 border-2 border-green-100 shadow-sm cursor-pointer rounded-xl bg-gradient-to-br from-green-50 to-white hover:border-green-300 hover:shadow-md">
                                    <div class="text-4xl text-center">{{ topic.icon }}</div>
                                    <h3 class="mt-4 text-xl font-semibold text-center text-green-800">{{ topic.name }}
                                    </h3>
                                    <p class="mt-2 text-sm text-center text-gray-600">Click to select this topic</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Ticket Form -->
                        <div v-else class="space-y-6">
                            <div class="flex items-center justify-between">
                                <button @click="goBackToTopics"
                                    class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Back to topics
                                </button>
                                <div class="space-x-2">
                                    <span class="px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                                        {{ selectedDepartmentName }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-sm font-medium text-purple-800 bg-purple-100 rounded-full">
                                        {{ selectedUnitName }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                        {{ selectedTopicName }}
                                    </span>
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold text-center text-gray-800">Tell us more about your issue
                            </h2>

                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="title" value="Title"
                                        class="block text-sm font-medium text-gray-700" />
                                    <TextInput v-model="form.title" id="title"
                                        class="block w-full px-4 py-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Briefly describe your issue" />
                                    <InputError :message="form.errors.title" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="description" value="Description"
                                        class="block text-sm font-medium text-gray-700" />
                                    <TextArea v-model="form.description" id="description"
                                        class="block w-full px-4 py-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        rows="5"
                                        placeholder="Please provide detailed information about your issue..." />
                                    <InputError :message="form.errors.description" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="priority" value="Priority"
                                        class="block text-sm font-medium text-gray-700" />
                                    <div class="mt-1 space-y-2">
                                        <div v-for="p in priorities" :key="p.value" class="flex items-center">
                                            <input v-model="form.priority" :id="`priority-${p.value}`" :value="p.value"
                                                type="radio" class="w-4 h-4 border-gray-300 focus:ring-blue-500">
                                            <label :for="`priority-${p.value}`"
                                                :class="`ml-3 block text-sm font-medium ${p.color}`">
                                                {{ p.label }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <PrimaryButton @click="submit" :disabled="form.processing"
                                        class="w-full px-6 py-3 text-base font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <span v-if="!form.processing">Submit Ticket</span>
                                        <span v-else class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2 -ml-1 animate-spin"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Processing...
                                        </span>
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>