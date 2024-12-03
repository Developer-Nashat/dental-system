/************* ✨ Codeium Command 🌟 *************/ #inertial vue registeration
page design
<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";

defineOptions({ layout: AuthLayout });
const visible = ref<boolean>(false);

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onError: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="تسجيل" />
    <v-card
        class="mx-auto pa-12 pb-8 mt-6"
        elevation="8"
        max-width="448"
        rounded="lg"
    >
        <v-card-title class="text-center text-h3 font-weight-black text-primary"
            >تسجيل
        </v-card-title>
        <v-form @submit.prevent="submit">
            <v-text-input
                class="mb-4"
                density="compact"
                prepend-inner-icon="mdi-account-outline"
                label="اسم المستخدم"
                variant="outlined"
                v-model="form.name"
                :error-messages="form.errors.name"
                :rules="[(v: string) => !!v || 'أدخل اسم المستخدم']"
            >
                <!-- <template v-slot:prepend-inner>
                <v-icon
                    v-if="form.errors.name"
                    color="red-darken-3"
                    icon="mdi-account-outline"
                ></v-icon>
                <v-icon
                    v-else
                    color="primary"
                    icon="mdi-account-outline"
                ></v-icon>
            </template> -->
            </v-text-input>

            <v-text-input
                class="mb-4"
                density="compact"
                label="البريد الألكتروني"
                prepend-inner-icon="mdi-email-outline"
                v-model="form.email"
                :error-messages="form.errors.email"
                :rules="[
                (v: string) => !!v || 'البريد الألكتروني إجباري',
                (v: string) => /.+@.+\..+/.test(v) || 'يجب ان يكون بصيغة بريد الكتروني',
            ]"
            >
            </v-text-input>

            <v-text-input
                class="mb-4"
                density="compact"
                label="كلمة السر"
                :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="visible = !visible"
                prepend-inner-icon="mdi-lock-outline"
                v-model="form.password"
                :error-messages="form.errors.password"
                :type="visible ? 'text' : 'password'"
                :rules="[
                (v: string) => !!v || 'كلمة السر إجابري',
                (v: string) =>
                    v.length >= 3 || 'طول كلمة السر يجب ان يكون ثلاثة احرف على الأقل',
            ]"
            >
                <!-- <template v-slot:append-inner>
                <v-icon
                    @click="visible = !visible"
                    :color="form.errors.password ? 'primary' : 'red-darken-3'"
                    :icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                ></v-icon>
            </template>

            <template v-slot:prepend-inner>
                <v-icon
                    :color="form.errors.password ? 'primary' : 'red-darken-3'"
                    icon="mdi-lock-outline"
                    border="primary sm"
                ></v-icon>
            </template> -->
            </v-text-input>

            <v-text-input
                class="mb-4"
                density="compact"
                label="تأكيد كلمة السر"
                :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="visible = !visible"
                prepend-inner-icon="mdi-lock-outline"
                v-model="form.password_confirmation"
                :error-messages="form.errors.password_confirmation"
                :type="visible ? 'text' : 'password'"
                :rules="[
                (v: any) => !!v || 'تأكيد كلمة السر إجباري',
                (v: string) => v === form.password || 'غير مطابقة لكلمة السر',
            ]"
            >
                <!-- <template v-slot:prepend-inner>
                <v-icon
                    :color="
                        form.errors.password_confirmation
                            ? 'primary'
                            : 'red-darken-3'
                    "
                    icon="mdi-lock-outline"
                    border="primary sm"
                ></v-icon>
            </template>

            <template v-slot:append-inner>
                <v-icon
                    @click="visible = !visible"
                    :color="
                        form.errors.password_confirmation
                            ? 'primary'
                            : 'red-darken-3'
                    "
                    :icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                ></v-icon>
            </template> -->
            </v-text-input>

            <v-btn-primary
                type="submit"
                class="mb-3 mt-5 font-weight-black text-h5"
                elevation="2"
                :disabled="form.processing"
                block
            >
                تسجيل
            </v-btn-primary>

            <v-card-text class="text-center">
                <v-btn
                    class="text-secondary text-decoration-none text-h6"
                    :href="route('login')"
                    rel="noopener noreferrer"
                    >لديك حساب مسبق
                    <v-icon icon="mdi-chevron-left"></v-icon>
                </v-btn>
            </v-card-text>
        </v-form>
    </v-card>
</template>
