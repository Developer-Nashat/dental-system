<script setup>
import { ref } from "vue";
const files = ref({
    html: "mdi-language-html5",
    js: "mdi-nodejs",
    json: "mdi-code-json",
    md: "mdi-language-markdown",
    pdf: "mdi-file-pdf-box",
    png: "mdi-file-image",
    txt: "mdi-file-document-outline",
    xls: "mdi-file-excel",
});
const items = ref([
    {
        id: 1,
        title: "قسم الإدارة",
        file: "html",
        children: [
            { id: 2, title: "العلاجات" },
            { id: 3, title: "المشاكل" },
            { id: 4, title: "معالجات المشاكل", children: null },
        ],
    },
    {
        id: 5,
        title: "قسم الإدارة",
        children: [
            {
                id: 6,
                title: "المعالجات",
                children: [
                    {
                        id: 7,
                        title: "src",
                        children: [
                            { id: 8, title: "index : ts" },
                            { id: 9, title: "bootstrap : ts" },
                        ],
                    },
                ],
            },
            {
                id: 10,
                title: "material2",
                children: [
                    {
                        id: 11,
                        title: "src",
                        children: [
                            { id: 12, title: "v-btn : ts" },
                            { id: 13, title: "v-card : ts" },
                            { id: 14, title: "v-window : ts" },
                        ],
                    },
                ],
            },
        ],
    },
    {
        id: 15,
        title: "Downloads",
        children: [
            { id: 16, title: "October : pdf" },
            { id: 17, title: "November : pdf" },
            { id: 18, title: "Tutorial : html" },
        ],
    },
    {
        id: 19,
        title: "Videos",
        children: [
            {
                id: 20,
                title: "Tutorials",
                children: [
                    { id: 21, title: "Basic layouts : mp4" },
                    { id: 22, title: "Advanced techniques : mp4" },
                    { id: 23, title: "All about app : dir" },
                ],
            },
            { id: 24, title: "Intro : mov" },
            { id: 25, title: "Conference introduction : avi" },
        ],
    },
]);
</script>
<template>
    <v-treeview
        :items="items"
        open-on-click="true"
        color="primary"
        item-value="id"
        activatable
    >
        <template v-slot:prepend="{ item, isOpen }">
            <v-icon v-if="!item.file">
                {{ isOpen ? "mdi-folder-open" : "mdi-folder" }}
            </v-icon>
            <v-icon v-else>
                {{ files[item.file] }}
            </v-icon>
        </template>
    </v-treeview>
</template>
