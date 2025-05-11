<!-- resources/js/Components/SelectInput.vue -->
<script setup>
    defineProps({
        modelValue: [String, Number],
        id: String,
        required: {
            type: Boolean,
            default: false
        }
    });

    defineEmits(['update:modelValue']);
</script>

<template>
    <select :id="id" :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" :required="required"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        <slot />
    </select>
</template>