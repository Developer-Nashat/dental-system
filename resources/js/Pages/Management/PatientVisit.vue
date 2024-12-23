<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import DentalChart from "../../Components/DentalChart.vue";
import { Tooth } from "../../types/Tooth";
import ProblemTreatment from "../../Components/ProblemTreatment.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps([
    "visit",
    "problemTreatments",
    "problems",
    "treatments",
    "teeth",
]);

const patient = props.visit.patient;
const dentist = props.visit.dentist;
const status = props.visit.status;
const teeth = props.visit.teeth;
const problems = props.visit.problems;
const treatments = props.visit.treatments;
const selectedTooth = ref<Tooth | null>(null);

const show = onMounted(() => {
    //   console.log("count " + props.problemTreatments[0].treatment.name);
});

// Reactive data
const upperTeeth = ref<Tooth[]>(
    Array.from({ length: 16 }, (_, i) => ({
        tooth_number: i + 1,
        hasProblem: teeth && teeth[i]?.tooth_number === i + 1 ? true : false,
    }))
);

const lowerTeeth = ref<Tooth[]>(
    Array.from({ length: 16 }, (_, i) => ({
        tooth_number: i + 17,
        hasProblem:
            teeth && teeth[i + 16]?.tooth_number === i + 17 ? true : false,
    }))
);

upperTeeth.value.forEach((upperTooth) => {
    const matchingTooth = teeth.find(
        (teethTooth: Tooth) =>
            teethTooth.tooth_number === upperTooth.tooth_number
    );

    if (matchingTooth) {
        upperTooth.hasProblem = true;
    }
});

lowerTeeth.value.forEach((lowerTooth) => {
    const matchingTooth = teeth.find(
        (teethTooth: Tooth) =>
            teethTooth.tooth_number === lowerTooth.tooth_number
    );
    if (matchingTooth) {
        lowerTooth.hasProblem = true;
    }
});

const refreshDentalChart = () => {
    console.log("teeth " + props.problemTreatments.teeth_id);

    router.visit(route("PatientVisit", props.visit.id), {
        method: "get",
        preserveScroll: true,
    });
};
</script>

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <v-card class="bg-white rounded-lg elevation-1">
                    <v-card-title
                        class="text-h5 pa-4 text-grey-darken-4 d-flex align-center"
                    >
                        <v-icon
                            icon="mdi-account-details"
                            class="me-2"
                            color="primary"
                        />
                        سجل المريض
                    </v-card-title>

                    <v-card-text>
                        <div class="d-flex flex-column gap-4">
                            <div
                                class="d-flex justify-space-between align-center"
                            >
                                <div class="d-flex align-center">
                                    <v-icon
                                        icon="mdi-account"
                                        class="me-2 text-primary"
                                        size="small"
                                    />
                                    <div>
                                        <div
                                            class="text-caption text-grey-darken-1"
                                        >
                                            اسم المريض
                                        </div>
                                        <div
                                            class="text-body-1 font-weight-medium"
                                        >
                                            {{ patient?.name || "-" }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-center">
                                    <v-icon
                                        icon="mdi-calendar-account"
                                        class="me-2 text-primary"
                                        size="small"
                                    />
                                    <div>
                                        <div
                                            class="text-caption text-grey-darken-1"
                                        >
                                            العمر
                                        </div>
                                        <div
                                            class="text-body-1 font-weight-medium"
                                        >
                                            {{ patient?.age || "-" }} سنة
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <v-divider />

                            <div
                                class="d-flex justify-space-between align-center"
                            >
                                <div class="d-flex align-center">
                                    <v-icon
                                        icon="mdi-gender-male-female"
                                        class="me-2 text-primary"
                                        size="small"
                                    />
                                    <div>
                                        <div
                                            class="text-caption text-grey-darken-1"
                                        >
                                            النوع
                                        </div>
                                        <div
                                            class="text-body-1 font-weight-medium"
                                        >
                                            {{
                                                patient?.gender === "M"
                                                    ? "ذكر"
                                                    : patient?.gender === "F"
                                                    ? "أنثى"
                                                    : "-"
                                            }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-center">
                                    <v-icon
                                        icon="mdi-phone"
                                        class="me-2 text-primary"
                                        size="small"
                                    />
                                    <div>
                                        <div
                                            class="text-caption text-grey-darken-1"
                                        >
                                            رقم الهاتف
                                        </div>
                                        <div
                                            class="text-body-1 font-weight-medium"
                                        >
                                            {{ patient?.phone_1 || "-" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Beginning of Dental Chart -->
        <!-- Dental Chart Section -->
        <v-row>
            <v-col cols="12" md="12">
                <v-card outlined class="dental-chart">
                    <v-card-title class="subtitle-1">مخطط الأسنان</v-card-title>
                    <v-card-text>
                        <div class="dental-grid">
                            <!-- Upper Teeth -->
                            <dental-chart :teeth="upperTeeth" />
                            <dental-chart
                                :teeth="lowerTeeth"
                                :is-bottom="true"
                            />
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- End of Dental Chart -->
        <v-row>
            <v-col cols="12">
                <ProblemTreatment
                    :problems="props.problems"
                    :treatments="props.treatments"
                    :teeth="props.teeth"
                    :ProblemTreatments="problemTreatments"
                    @updateProblemTreatment="refreshDentalChart"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <div class="bg-white">Problems and treatments</div>
            </v-col>
        </v-row>
    </v-container>
    <div></div>
</template>

<style scoped>
dental-grid {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    padding: 1rem;
}

.chart-rounded {
    border-radius: 50%;
    width: 200px;
    height: 200px;
}
</style>
