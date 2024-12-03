<template>
    <v-treeview
        :items="items"
        open-on-click
        color="primary"
        item-value="id"
        activatable
    >
        <template #prepend="{ item, isOpen }">
            <v-icon v-if="!item.file">
                {{ isOpen ? "mdi-folder-open" : "mdi-folder" }}
            </v-icon>
            <v-icon v-else>
                {{ files[item.file] }}
            </v-icon>
        </template>

        <template #title="{ item }">
            <div v-if="item.link" @click="navigate(item.link)" class="bg-blue">
                {{ item.title }}
            </div>
            <span v-else>{{ item.title }}</span>
        </template>
    </v-treeview>
</template>

<script setup lang="ts">
import { ref } from "vue";

const items = ref([
    {
        id: 1,
        title: "Root",
        children: [
            {
                id: 2,
                title: "Branch 1",
                children: [
                    { id: 3, title: "Leaf 1" },
                    { id: 4, title: "Leaf 2" },
                ],
            },
            {
                id: 5,
                title: "Branch 2",
                children: [
                    { id: 6, title: "Leaf 3" },
                    { id: 7, title: "Leaf 4" },
                ],
            },
        ],
    },
]);

const files = ref({
    html: "mdi-language-html5",
    js: "mdi-nodejs",
    json: "mdi-code-json",
    // Add other file type icons as needed
});

const navigate = (link: string) => {
    // Handle navigation here
};
</script>

<style scoped>
.bg-blue {
    background-color: red;
}
</style>
