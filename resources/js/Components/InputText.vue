<script setup lang="ts">
const model = defineModel({
    type: null,
    required: true,
});

defineProps({
    name: {
        type: String,
        required: true,
    },

    type: {
        type: String,
        default: "text",
    },

    message: String,
});
</script>

<template>
    <div></div>
</template>
