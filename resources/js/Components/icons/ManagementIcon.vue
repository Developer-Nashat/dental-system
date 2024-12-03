<script setup>
defineProps({
    fill: {
        type: String,
        default: "#3F51B5",
    },
});
</script>
<template>
    <svg
        :fill="fill"
        width="20px"
        height="20px"
        viewBox="0 0 24 24"
        id="dashboard"
        class="icon glyph"
    >
        <rect x="2" y="2" width="9" height="11" rx="2"></rect>
        <rect x="13" y="2" width="9" height="7" rx="2"></rect>
        <rect x="2" y="15" width="9" height="7" rx="2"></rect>
        <rect x="13" y="11" width="9" height="11" rx="2"></rect>
    </svg>
</template>
