<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import { User } from "../../types/User";

defineOptions({ layout: AuthLayout });

const visible = ref<boolean>(false);

// const emit = defineEmits(["increment"]);

const props = defineProps<{
    user: User;
}>();

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

// const increaseStep = () => {
//     emit("increment");
// };

const submit = () => {
    form.post(route("login"), {
        onError: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="دخول" />
    <div>
        <!-- <v-img
            class="mx-auto my-6"
            max-width="228"
            src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
        >
        </v-img> -->

        <v-card
            class="mx-auto pa-12 pb-8 mt-8"
            elevation="8"
            max-width="448"
            rounded="lg"
        >
            <v-card-title
                class="text-center text-h3 font-weight-black text-darker"
            >
                دخول
            </v-card-title>
            <v-form fast-fail @submit.prevent="submit">
                <v-text-input
                    class="mb-4"
                    density="compact"
                    label="البريد الألكتروني"
                    prepend-inner-icon="mdi-email-outline"
                    v-model="form.email"
                    :error-messages="form.errors.email"
                    :rules="[
                (v: string) => !!v || 'أدخل البريد الألكتروني',
                (v: string) => /.+@.+\..+/.test(v) || 'يجب ان يكون بصيغة بريد الكتروني',
            ]"
                >
                </v-text-input>

                <v-text-input
                    density="compact"
                    label="كلمة السر"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="visible = !visible"
                    prepend-inner-icon="mdi-lock-outline"
                    v-model="form.password"
                    :error-messages="form.errors.password"
                    :type="visible ? 'text' : 'password'"
                    :rules="[
                (v: string) => !!v || 'يجب ان تدخل كلمة السر',
            ]"
                >
                </v-text-input>

                <v-checkbox
                    v-model="form.remember"
                    color="primary"
                    label="تذكرني"
                    value="form.remember"
                ></v-checkbox>

                <v-btn-primary
                    type="submit"
                    class="mb-8 font-weight-black text-h5"
                    elevation="2"
                    block
                >
                    دخول
                </v-btn-primary>
            </v-form>

            <v-card-text class="text-center">
                <v-btn
                    class="text-secondary text-decoration-none text-h6"
                    :href="route('register')"
                    rel="noopener noreferrer"
                    border="secondary"
                >
                    سجل الان
                    <v-icon icon="mdi-chevron-left"></v-icon>
                </v-btn>
            </v-card-text>
        </v-card>
    </div>
</template>
