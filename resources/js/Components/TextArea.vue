<!-- resources/js/Components/TextArea.vue -->
<script setup>
    defineProps({
        modelValue: String,
        id: String,
        rows: {
            type: [String, Number],
            default: 3
        },
        placeholder: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        }
    });

    defineEmits(['update:modelValue']);
</script>

<template>
    <textarea :id="id" :rows="rows" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder" :required="required"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
</template>