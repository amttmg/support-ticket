<!-- resources/js/Components/Comment.vue -->
<script setup>
    import { Link, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        comment: Object,
        ticketId: Number,
    });

    const replyForm = useForm({
        body: '',
        parent_id: props.comment.id,
    });

    const submitReply = () => {
        replyForm.post(route('tickets.comments.store', { ticket: props.ticketId }), {
            preserveScroll: true,
            onSuccess: () => replyForm.reset(),
        });
    };
</script>

<template>
    <div class="p-4 border border-gray-200 rounded-lg" :class="{ 'ml-8': comment.parent_id }">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-8 h-8 mr-3 font-bold text-gray-600 bg-gray-300 rounded-full">
                    {{ (comment.user?.name || comment.guest_name)?.charAt(0).toUpperCase() }}
                </div>
                <div>
                    <span class="font-semibold">{{ comment.user?.name || comment.guest_name }}</span>
                    <span class="ml-2 text-sm text-gray-500">{{ $filters.timeAgo(comment.created_at) }}</span>
                </div>
            </div>
        </div>
        <p class="mt-2 whitespace-pre-line" v-html="comment.body"></p>



        <!-- Child Comments -->
        <div v-if="comment.children && comment.children.length" class="mt-4 space-y-4">
            <Comment v-for="child in comment.children" :key="child.id" :comment="child" :ticket-id="ticketId" />
        </div>

        <!-- Reply Form (toggleable in a real implementation) -->
        <form v-if="!comment.parent_id" @submit.prevent="submitReply" class="mt-4 mb-4 ml-8">
            <textarea v-model="replyForm.body"
                class="w-full p-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                rows="2" placeholder="Write a reply..." required></textarea>
            <div class="flex justify-end mt-1">
                <button type="submit"
                    class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1"
                    :disabled="replyForm.processing">
                    Reply
                </button>
            </div>
        </form>
    </div>
</template>