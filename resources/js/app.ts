import "./bootstrap";

import "../css/app.css";

import { createApp, h, DefineComponent } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/src/js";
import Layout from "./Layouts/Layout.vue";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import Toast, { PluginOptions } from "vue-toastification";
import "vue-toastification/dist/index.css";

// Vuetify
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "@mdi/font/css/materialdesignicons.css";
import colors from "vuetify/util/colors";
import { VDateInput } from "vuetify/labs/VDateInput";
import { VTreeview } from "vuetify/labs/VTreeview";
import { VTimePicker } from "vuetify/labs/VTimePicker";

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: "#3F51B5",
                    secondary: "#606FC7",
                    background: colors.indigo.lighten5,
                    darker: colors.indigo.darken4,
                },
            },
        },
    },

    aliases: {
        VBtnPrimary: components.VBtn,
        VBtnSecondary: components.VBtn,
        VBtnAdd: components.VBtn,
        VBtnSave: components.VBtn,
        VBtnDelete: components.VBtn,
        VBtnCancel: components.VBtn,
        VTextInput: components.VTextField,
    },
    defaults: {
        VBtnPrimary: {
            variant: "flat",
            color: "primary",
            size: "large",
        },
        VTextInput: {
            variant: "outlined",
            color: "primary",
            density: "compact",
        },
        VAutocomplete: {
            color: "primary",
            variant: "outlined",
            density: "compact",
        },
        VCombobox: {
            color: "primary",
            variant: "outlined",
            density: "compact",
        },
        VSelect: {
            color: "primary",
            variant: "outlined",
            density: "compact",
        },
        VBtnAdd: {
            class: "mb-2 font-weight-black text-h6",
            color: "primary",
            variant: "flat",
        },
        VBtnSave: {
            color: "primary",
            variant: "flat",
        },
        VBtnDelete: {
            color: "red-darken-1",
            variant: "flat",
        },
        VBtnCancel: { color: "primary", variant: "tonal" },

        // VDataTable: {
        //     NoDataText: "لاتوجد بيانات",
        //     Hover: true,
        //     Color: "indigo-lighten-5",
        // },
    },
});

createInertiaApp({
    title: (title) => `نظام ادارة عيادة الأسنان - ${title}`,

    resolve: async (name) => {
        let page = (await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        )) as { default: DefineComponent };

        page.default.layout = page.default.layout || Layout;
        return page.default;
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(Toast, {
                transition: "Vue-Toastification__slideBlurred",
                maxToasts: 20,
                newestOnTop: true,
            })
            .component("Head", Head)
            .component("Link", Link)
            .component("VDateInput", VDateInput)
            .component("VTreeview", VTreeview)
            .component("VTimePicker", VTimePicker)
            .mount(el);
    },

    progress: {
        color: "#fff",
        includeCSS: true,
        showSpinner: true,
    },
});
