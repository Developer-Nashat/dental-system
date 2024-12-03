<script setup>
import { ref } from "vue";

const open = ref(["Users"]);
const openManagement = ref(["management"]);
const mainEntaries = ref([
    ["المشاكل", "mdi-cog-outline", "problemCatalogs"],
    ["المعالجة", "mdi-account-multiple-outline", "treatments"],
]);
const ops = ref([
    ["الزيارات", "mdi-cog-outline"],
    ["معالجات المرضى", "mdi-account-multiple-outline"],
]);
const admins = ref([
    ["Management", "mdi-account-multiple-outline"],
    ["Settings", "mdi-cog-outline"],
]);
const cruds = ref([
    ["Create", "mdi-plus-outline"],
    ["Read", "mdi-file-outline"],
    ["Update", "mdi-update"],
    ["Delete", "mdi-delete"],
]);
</script>
<template>
    <v-list v-model:opened="openManagement">
        <v-list-item prepend-icon="mdi-home" title="Home"> </v-list-item>

        <v-list-group value="openManagement">
            <template v-slot:activator="{ props }">
                <v-list-item
                    class="bg-indigo-lighten-4"
                    color="primary"
                    v-bind="props"
                    prepend-icon="mdi-account-circle"
                    title="القسم الإداري"
                ></v-list-item>
            </template>

            <v-list-group value="mainEntary">
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        title="المدخلات الرئيسية"
                    ></v-list-item>
                </template>

                <v-list-item
                    v-for="([title, icon, link], i) in mainEntaries"
                    :key="i"
                    :prepend-icon="icon"
                    :value="title"
                    color="primary"
                    slim="true"
                    nav
                    class="content-end"
                >
                    <Link :href="route(link)">{{ title }}</Link>
                </v-list-item>
            </v-list-group>

            <v-list-group value="ops">
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        class="mb-2"
                        title="العمليات"
                    ></v-list-item>
                </template>

                <div
                    v-for="([title, icon], i) in ops"
                    :key="i"
                    class="content-end pr-16 mb-3"
                >
                    <div class="pr-5 block">
                        <v-icon class="pl-3"> {{ icon }} </v-icon>
                        <Link
                            class="rounded mx-auto"
                            as="button"
                            :href="route('Home')"
                            v-ripple="{
                                class: `text-indigo-lighten-1`,
                            }"
                            >{{ title }}</Link
                        >
                    </div>
                </div>
            </v-list-group>
        </v-list-group>
    </v-list>

    <!-- <v-list v-model:opened="open">
        <v-list-item prepend-icon="mdi-home" title="Home"></v-list-item>

        <v-list-group value="Users">
            <template v-slot:activator="{ props }">
                <v-list-item
                    v-bind="props"
                    prepend-icon="mdi-account-circle"
                    title="Users"
                ></v-list-item>
            </template>

            <v-list-group value="Admin">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" title="Admin"></v-list-item>
                </template>

                <v-list-item
                    v-for="([title, icon], i) in admins"
                    :key="i"
                    :prepend-icon="icon"
                    :value="title"
                    color="primary"
                    slim="true"
                    nav
                    class="content-end"
                >
                    <Link>{{ title }}</Link></v-list-item
                >
            </v-list-group>

            <v-list-group value="Actions">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" title="Actions"></v-list-item>
                </template>

                <v-list-item
                    v-for="([title, icon], i) in cruds"
                    :key="i"
                    :prepend-icon="icon"
                    :title="title"
                    :value="title"
                ></v-list-item>
            </v-list-group>
        </v-list-group>
    </v-list> -->
</template>
