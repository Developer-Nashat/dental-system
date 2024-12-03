<script setup lang="ts">
import { defineAsyncComponent, computed, PropType } from "vue";

const props = defineProps({
    name: {
        type: String as PropType<string | null>,
        required: true,
    },
    style: {
        type: [Array, Object],
    },
});
const dynamicComponent = computed(() => {
    const name = props.name;

    return defineAsyncComponent(() => import(`./icons/${name}.vue`));
});
</script>

<template>
    <component :is="dynamicComponent" :class="'style'" />
</template>
