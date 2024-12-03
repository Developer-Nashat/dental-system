<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const drawer = ref<boolean>(false);

const navigate = (routeName: string, method: "get" | "post" = "get") => {
    router.visit(routeName, { method });
};
</script>

<template>
    <v-app>
        <v-locale-provider rtl>
            <v-app-bar prominent flat density="comfortable">
                <v-toolbar-title
                    class="pr-16 text-grey-darken-3 font-weight-black text-h5"
                    text="نظام إدارة عيادات الأسنان"
                />
                <v-spacer></v-spacer>
                <!-- <v-btn
                    v-if="$page.component !== 'Auth/Login'"
                    text="Login"
                    :href="route('login')"
                    append-icon="mdi-login"
                    variant="elevated"
                    color="indigo"
                /> -->
                <Link
                    v-if="$page.component !== 'Auth/Register'"
                    class="elevation-4 rounded ml-3 bg-primary pt-1 pb-2 px-12"
                    :href="route('register')"
                    as="button"
                    preserve-state
                    v-ripple
                    >تسجيل</Link
                >
                <Link
                    v-else="$page.component !== 'Auth/Login'"
                    class="elevation-4 rounded ml-3 bg-primary pt-1 pb-2 px-12"
                    :href="route('login')"
                    as="button"
                    preserve-state
                    v-ripple
                    >دخول</Link
                >

                <!-- <v-btn
                    v-if="$page.component !== 'Auth/Register'"
                    text="Register"
                    @click="navigate('register')"
                    append-icon="mdi-login"
                    variant="elevated"
                    color="indigo"
                /> -->

                <!-- <template v-if="$vuetify.display.mdAndUp">

                    <v-btn icon="mdi-filter" variant="text"></v-btn>
                </template> -->

                <!-- <v-btn icon="mdi-dots-vertical" variant="text">
                    <v-icon color="primary"> </v-icon>
                </v-btn> -->
            </v-app-bar>

            <v-main class="content-center">
                <v-slide-x-transition>
                    <slot />
                </v-slide-x-transition>
            </v-main>
        </v-locale-provider>
    </v-app>
</template>
