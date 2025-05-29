<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, router } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';

    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import TextArea from '@/Components/TextArea.vue';

    // Reactive state
    const selectedDepartment = ref(null);
    const selectedUnit = ref(null);
    const selectedTopic = ref(null);
    const departments = ref([]);
    const units = ref([]);
    const topics = ref([]);

    const loadingDepartments = ref(false);
    const loadingUnits = ref(false);
    const loadingTopics = ref(false);

    // Form handling
    const form = useForm({
        title: '',
        description: '',
        priority: 'medium',
        support_topic_id: '',
    });

    // Priority options
    const priorities = [
        { value: 'low', label: 'Low', color: 'text-green-600' },
        { value: 'medium', label: 'Medium', color: 'text-yellow-600' },
        { value: 'high', label: 'High', color: 'text-red-600' },
    ];

    // Computed properties
    const selectedDepartmentName = computed(() => {
        const dept = departments.value.find(d => d.id === selectedDepartment.value);
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

    // Fetch departments on mount
    onMounted(async () => {
        try {
            loadingDepartments.value = true;
            const response = await axios.get('/departments');
            departments.value = response.data;
        } catch (error) {
            console.error('Error fetching departments:', error);
        } finally {
            loadingDepartments.value = false;
        }
    });

    // Department selected
    const selectDepartment = async (departmentId) => {
        selectedDepartment.value = departmentId;
        selectedUnit.value = null;
        selectedTopic.value = null;

        try {
            loadingUnits.value = true;
            const response = await axios.get(`/departments/${departmentId}/units`);
            units.value = response.data;
        } catch (error) {
            console.error('Error fetching units:', error);
        } finally {
            loadingUnits.value = false;
        }
    };

    // Unit selected
    const selectUnit = async (unitId) => {
        selectedUnit.value = unitId;
        selectedTopic.value = null;

        try {
            loadingTopics.value = true;

            const response = await axios.get(`/units/${unitId}/topics`);
            topics.value = response.data;
        } catch (error) {
            console.error('Error fetching topics:', error);
        } finally {
            loadingTopics.value = false;

        }
    };

    // Topic selected
    const selectTopic = (topicId) => {
        selectedTopic.value = topicId;
        form.support_topic_id = topicId;
    };

    // Navigation functions
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
    // Add this new ref for search term
    const searchTerm = ref('');

    // Add this computed property to filter topics
    const filteredTopics = computed(() => {
        if (!searchTerm.value) return topics.value;
        return topics.value.filter(topic =>
            topic.name.toLowerCase().includes(searchTerm.value.toLowerCase())
        );
    });
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
                                <!-- Step 1: Department -->
                                <div :class="`flex items-center justify-center w-10 h-10 rounded-full ${
                                        !selectedDepartment ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800'
                                    } font-semibold`">
                                    1
                                </div>
                                <div class="w-16 h-1 mx-2 bg-blue-200"></div>

                                <!-- Step 2: Unit -->
                                <div :class="`flex items-center justify-center w-10 h-10 rounded-full ${
                                        selectedDepartment && !selectedUnit ? 'bg-blue-600 text-white' : 
                                        selectedUnit ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-400'
                                    } font-semibold`">
                                    2
                                </div>
                                <div class="w-16 h-1 mx-2 bg-blue-200"></div>

                                <!-- Step 3: Topic -->
                                <div :class="`flex items-center justify-center w-10 h-10 rounded-full ${
                                        selectedUnit && !selectedTopic ? 'bg-blue-600 text-white' : 
                                        selectedTopic ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-400'
                                    } font-semibold`">
                                    3
                                </div>
                                <div class="w-16 h-1 mx-2 bg-blue-200"></div>

                                <!-- Step 4: Form -->
                                <div :class="`flex items-center justify-center w-10 h-10 rounded-full ${
                                        selectedTopic ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400'
                                    } font-semibold`">
                                    4
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Select Department -->
                        <div v-if="!selectedDepartment" class="space-y-6">
                            <h2 class="text-xl font-semibold text-center text-gray-800">Which department can help you?
                            </h2>
                            <div v-if="loadingDepartments" class="flex justify-center py-8">
                                <div
                                    class="w-8 h-8 border-4 border-blue-500 rounded-full border-t-transparent animate-spin">
                                </div>
                            </div>
                            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <div v-for="dept in departments" :key="dept.id" @click="selectDepartment(dept.id)"
                                    class="block p-8 transition duration-200 border-2 border-blue-200 shadow-sm cursor-pointer rounded-xl bg-gradient-to-br from-blue-50 to-white hover:border-blue-300 hover:shadow-md">
                                    <!-- <div class="text-4xl text-center">
                                        💻
                                    </div> -->
                                    <h3 class="mt-4 text-xl font-semibold text-center text-blue-800">{{ dept.name }}
                                    </h3>
                                    <p class="mt-2 text-sm text-center text-gray-600">Click to select this department
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Select Unit -->
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
                            <div v-if="loadingUnits" class="flex justify-center py-8">
                                <div
                                    class="w-8 h-8 border-4 border-blue-500 rounded-full border-t-transparent animate-spin">
                                </div>
                            </div>
                            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <div v-for="unit in units" :key="unit.id" @click="selectUnit(unit.id)"
                                    class="block p-8 transition duration-200 border-2 border-blue-100 shadow-sm cursor-pointer rounded-xl bg-gradient-to-br from-blue-50 to-white hover:border-blue-300 hover:shadow-md">
                                    <h3 class="text-xl font-semibold text-center text-blue-800">{{ unit.name }}</h3>
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

                            <!-- Search Box -->
                            <!-- <div class="relative max-w-md mx-auto">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input v-model="searchTerm" type="text"
                                    class="w-full py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    placeholder="Search topics...">
                            </div> -->
                            <div v-if="loadingTopics" class="flex justify-center py-8">
                                <div
                                    class="w-8 h-8 border-4 border-blue-500 rounded-full border-t-transparent animate-spin">
                                </div>
                            </div>

                            <!-- Topics Grid - Made smaller with p-6 instead of p-8 -->
                            <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <div v-for="topic in filteredTopics" :key="topic.id" @click="selectTopic(topic.id)"
                                    class="block p-6 transition duration-200 border-2 border-blue-100 shadow-sm cursor-pointer rounded-xl bg-gradient-to-br from-blue-50 to-white hover:border-blue-300 hover:shadow-md">
                                    <h3 class="text-lg font-semibold text-center text-blue-800">{{ topic.name }}</h3>
                                    <p class="mt-1 text-xs text-center text-gray-600">Click to select this topic</p>
                                </div>
                            </div>

                            <!-- No results message -->
                            <!-- <div v-if="filteredTopics.length === 0" class="py-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">No topics found</h3>
                                <p class="mt-1 text-gray-500">Try adjusting your search or filter</p>
                            </div> -->
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
                                    <InputLabel for="title" value="Title" />
                                    <TextInput v-model="form.title" id="title" type="text" class="block w-full mt-1"
                                        placeholder="Briefly describe your issue" />
                                    <InputError :message="form.errors.title" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="description" value="Description" />
                                    <TextArea v-model="form.description" id="description" class="block w-full mt-1"
                                        rows="5"
                                        placeholder="Please provide detailed information about your issue..." />
                                    <InputError :message="form.errors.description" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel value="Priority" />
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