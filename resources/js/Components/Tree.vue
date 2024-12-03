<script setup lang="ts">
import { items } from "../items.js";
import SvgIcon from "../Components/SvgIcon.vue";
import { computed, defineAsyncComponent, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

const active = ref([]);
const open = ref([]);

watch(active, () => {
    if (computedItems.value?.link) {
        navigateTo(computedItems.value?.link, "get");
    }
});

function findById(id: number, items: readonly any[]): any | null {
    for (const item of items) {
        if (item.id === id) return item;

        if (item.children) {
            const foundItem = findById(id, item.children);
            if (foundItem) return foundItem;
        }
    }

    return null;
}

const computedItems = computed(() => {
    return active.value.length ? findById(active.value[0], items) : null;
});

const navigateTo = (route: string, requestMethod: "get" | "post" = "get") => {
    router.visit(route, { method: requestMethod });
};
</script>

<template>
    <v-treeview
        v-model:activated="active"
        v-model:opened="open"
        :items="items"
        item-title="title"
        item-value="id"
        collapse-icon="mdi-menu-down"
        expand-icon="mdi-menu-left"
        active-color="#5C6BC0"
        bg-color="grey-lighten-5"
        base-color="primary"
        activatable
        open-on-click
        elevation="1"
        height="100%"
        border
    >
        <template #prepend="{ item }">
            <SvgIcon
                v-if="!item.file"
                name="ManagementIcon"
                width="28"
                height="28"
            />

            <SvgIcon class="me-2" :name="item.file" width="28" height="28" />
        </template>
    </v-treeview>
</template>
