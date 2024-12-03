<script setup lang="ts">
import { ref } from "vue";
import Login from "./Login.vue";
import Register from "./Register.vue";
import AuthLayout from "../../Layouts/AuthLayout.vue";

defineOptions({ layout: AuthLayout });

const step = ref<number>(1);

function incrementStep() {
    step.value += 1;
}
</script>
<template>
    <v-window v-model="step" class="pb-8">
        <v-window-item :value="1">
            <div>
                <Login />
            </div>
        </v-window-item>
        <v-window-item :value="2">
            <div>
                <Register />
            </div>
        </v-window-item>
    </v-window>

    <!-- <v-btn v-if="step < 3" color="primary" variant="flat" @click="step++">
        Next
    </v-btn> -->
</template>

<style scoped></style>
