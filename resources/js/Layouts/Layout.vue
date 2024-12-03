<script setup lang="ts">
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { PageProps } from "../types/PageProps";
import Tree from "../Components/Tree.vue";
import TreeView from "../Components/TreeView.vue";
// import Sidebar from "../Components/sidebar.vue";
// import SidebarV2 from "../Components/SidebarV2.vue";

const page = usePage();
const pageProps = page.props as unknown as PageProps;
const user = computed(() => pageProps.auth.user);

const drawer = ref<boolean>(true);

const navigate = (routeName: string, method: "get" | "post" = "get") => {
    router.visit(routeName, { method });
};
</script>

<template>
    <v-app>
        <v-locale-provider rtl>
            <v-app-bar prominent flat density="comfortable" color="primary">
                <v-app-bar-nav-icon
                    color="text-grey-lighten-5"
                    border=" primary sm"
                    class="rounded-sm"
                    asolute
                    elevate-on-scroll
                    scroll-target="#scrolling-techniques-7"
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>

                <v-toolbar-title
                    class="text-grey-lighten-5 font-weight-bold"
                    text="نظام إدارة عيادات الأسنان"
                />
                <v-spacer></v-spacer>

                <v-btn color="text-grey-lighten-5 ml-12" icon="mdi-bell">
                </v-btn>

                <!-- <v-btn text="Login" @click="navigateTo('login')" /> -->
                <v-btn
                    variant="tonal"
                    v-if="user"
                    color="bg-grey-lighten-5"
                    text="Logout"
                    @click="navigate('logout', 'post')"
                />

                <!-- <template v-if="$vuetify.display.mdAndUp">

                    <v-btn icon="mdi-filter" variant="text"></v-btn>
                </template> -->

                <!-- <v-btn icon="mdi-dots-vertical" variant="text">
                    <v-icon color="primary"> </v-icon>
                </v-btn> -->
            </v-app-bar>

            <v-navigation-drawer
                :location="$vuetify.display.mobile ? 'bottom' : 'left'"
                width="200"
            >
            </v-navigation-drawer>

            <v-navigation-drawer
                mini-variant
                mini-variant-width="90"
                v-model="drawer"
                :location="$vuetify.display.mobile ? 'bottom' : 'right'"
                class="content-center overflow-auto"
            >
                <!-- <SidebarV2 /> -->
                <Tree />
            </v-navigation-drawer>

            <v-main class="content-center">
                <v-slide-x-transition hide-on-leave leave-absolute>
                    <slot />
                </v-slide-x-transition>
            </v-main>
        </v-locale-provider>
    </v-app>
</template>
