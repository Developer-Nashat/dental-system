<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import DentalChart from "../../Components/DentalChart.vue";
import { Tooth } from "../../types/Tooth";

const props = defineProps(["visit", "problemTreatments"]);
const patient = props.visit.patient;
const dentist = props.visit.dentist;
const status = props.visit.status;
const teeth = props.visit.teeth;
const problems = props.visit.problems;
const treatments = props.visit.treatments;
const selectedTooth = ref<Tooth | null>(null);
// const problemTreatments = props.visit.problemTreatments;

const show = onMounted(() => {
    console.log("count " + teeth?.[1]?.tooth_number);
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

// Computed
const hasSelectedTeeth = computed(() =>
    [...upperTeeth.value, ...lowerTeeth.value].some((tooth) => tooth.hasProblem)
);

const selectTooth = (tooth: Tooth) => {
    tooth.hasProblem = !tooth.hasProblem;
    selectedTooth.value = tooth.hasProblem ? tooth : null;
};

const hasToothProblem = (tooth: Tooth) => {
    return tooth.hasProblem;
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
                            <dental-chart
                                :is-clickable="true"
                                :teeth="upperTeeth"
                            />
                            <dental-chart
                                :teeth="lowerTeeth"
                                :is-bottom="true"
                            />
                            <!-- <div class="teeth-row upper">
                                <div
                                    v-for="tooth in upperTeeth"
                                    :key="tooth.tooth_number"
                                    class="tooth"
                                    @click="selectTooth(tooth)"
                                >
                                    <div class="tooth-tooth_number">
                                        {{ tooth.tooth_number }}
                                    </div>
                                    <v-tooltip
                                        :text="tooth.tooth_number"
                                        activator="parent"
                                        location="top"
                                    >
                                    </v-tooltip>
                                    <v-icon
                                        size="40"
                                        :color="
                                            tooth.hasProblem ? 'red' : 'primary'
                                        "
                                        >mdi-tooth-outline</v-icon
                                    >
                                </div>
                            </div> -->

                            <!-- Lower Teeth -->
                            <!-- <div class="teeth-row lower">
                                <div
                                    v-for="tooth in lowerTeeth"
                                    :key="tooth.tooth_number"
                                    class="tooth"
                                    @click="selectTooth(tooth)"
                                >
                                    <v-tooltip
                                        :text="tooth.tooth_number"
                                        activator="parent"
                                        location="bottom"
                                    >
                                    </v-tooltip>
                                    <v-icon
                                        size="40"
                                        :color="
                                            tooth.hasProblem ? 'red' : 'primary'
                                        "
                                        >mdi-tooth-outline</v-icon
                                    >
                                    <div
                                        class="tooth-tooth_number"
                                        :class="
                                            tooth.hasProblem ? 'text-red' : ''
                                        "
                                    >
                                        {{ tooth.tooth_number }}
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- End of Dental Chart -->
        <v-row>
            <v-col cols="12">
                <v-card class="bg-white rounded-lg elevation-1">
                    <v-card-title
                        class="text-h5 pa-4 text-grey-darken-4 d-flex align-center"
                    >
                        <v-icon
                            icon="mdi-tooth"
                            class="me-2"
                            color="primary"
                            size="small"
                        />
                        المشاكل و العلاج
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
                                            المشكلة
                                        </div>
                                        <div
                                            class="text-body-1 font-weight-medium"
                                        >
                                            <!-- {{ problems[0]?.name || "-" }} -->
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
                                            العلاج
                                        </div>
                                        <div
                                            class="text-body-1 font-weight-medium"
                                        >
                                            <!-- {{ treatments[0]?.name || "-" }} -->
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

.teeth-row {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
}

.tooth {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.tooth:hover {
    background-color: rgba(63, 81, 181, 0.05);
}

.tooth.has-problem {
    background-color: rgba(244, 67, 54, 0.1);
}

.tooth-number {
    font-size: 0.75rem;
    color: rgba(0, 0, 0, 0.6);
}

.upper {
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

.lower {
    border-top: 2px solid rgba(0, 0, 0, 0.1);
}
</style>
