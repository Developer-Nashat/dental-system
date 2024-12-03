<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12">
        <v-card class="mb-4">
          <v-card-title class="text-h5 font-weight-bold primary white--text">
            Dental Problem & Treatment Chart
          </v-card-title>

          <v-card-text>
            <v-row>
              <!-- Patient Info Section -->
              <v-col cols="12" md="4">
                <v-card outlined>
                  <v-card-title class="subtitle-1">Patient Information</v-card-title>
                  <v-card-text>
                    <v-text-field
                      v-model="patientName"
                      label="Patient Name"
                      outlined
                      dense
                    ></v-text-field>
                    <v-text-field
                      v-model="patientId"
                      label="Patient ID"
                      outlined
                      dense
                    ></v-text-field>
                    <v-menu
                      v-model="dateMenu"
                      :close-on-content-click="false"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="treatmentDate"
                          label="Treatment Date"
                          readonly
                          outlined
                          dense
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="treatmentDate"
                        @input="dateMenu = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Dental Chart Section -->
              <v-col cols="12" md="8">
                <v-card outlined class="dental-chart">
                  <v-card-title class="subtitle-1">Dental Chart</v-card-title>
                  <v-card-text>
                    <div class="dental-grid">
                      <!-- Upper Teeth -->
                      <div class="teeth-row upper">
                        <div
                          v-for="tooth in upperTeeth"
                          :key="tooth.number"
                          class="tooth"
                          :class="{ 'has-problem': tooth.hasProblem }"
                          @click="selectTooth(tooth)"
                        >
                          <div class="tooth-number">{{ tooth.number }}</div>
                          <v-icon size="40" color="primary">mdi-tooth-outline</v-icon>
                        </div>
                      </div>

                      <!-- Lower Teeth -->
                      <div class="teeth-row lower">
                        <div
                          v-for="tooth in lowerTeeth"
                          :key="tooth.number"
                          class="tooth"
                          :class="{ 'has-problem': tooth.hasProblem }"
                          @click="selectTooth(tooth)"
                        >
                          <v-icon size="40" color="primary">mdi-tooth-outline</v-icon>
                          <div class="tooth-number">{{ tooth.number }}</div>
                        </div>
                      </div>
                    </div>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>

            <!-- Treatment Details Section -->
            <v-row class="mt-4">
              <v-col cols="12">
                <v-card outlined>
                  <v-card-title class="subtitle-1">Treatment Details</v-card-title>
                  <v-card-text>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-select
                          v-model="selectedProblem"
                          :items="problems"
                          label="Problem"
                          outlined
                          dense
                        ></v-select>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-select
                          v-model="selectedTreatment"
                          :items="treatments"
                          label="Treatment"
                          outlined
                          dense
                        ></v-select>
                      </v-col>
                      <v-col cols="12">
                        <v-textarea
                          v-model="notes"
                          label="Notes"
                          outlined
                          auto-grow
                          rows="3"
                        ></v-textarea>
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>

            <!-- Action Buttons -->
            <v-row class="mt-4">
              <v-col cols="12" class="text-right">
                <v-btn color="error" class="mr-4" @click="resetForm"> Reset </v-btn>
                <v-btn color="primary" @click="saveTreatment"> Save Treatment </v-btn>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from "vue";
import { Head } from "@inertiajs/vue3";

// Types
interface Tooth {
  number: number;
  hasProblem: boolean;
}

// Constants
const PROBLEMS = [
  "Cavity",
  "Gingivitis",
  "Root Canal",
  "Broken Tooth",
  "Wisdom Tooth",
  "Periodontitis",
] as const;

const TREATMENTS = [
  "Filling",
  "Cleaning",
  "Extraction",
  "Root Canal Treatment",
  "Crown",
  "Bridge",
] as const;

// State
const patientName = ref<string>("");
const patientId = ref<string>("");
const treatmentDate = ref<string>(new Date().toISOString().substr(0, 10));
const dateMenu = ref<boolean>(false);
const selectedTooth = ref<Tooth | null>(null);
const notes = ref<string>("");
const selectedProblem = ref<typeof PROBLEMS[number] | null>(null);
const selectedTreatment = ref<typeof TREATMENTS[number] | null>(null);

// Reactive data
const upperTeeth = reactive<Tooth[]>(
  Array.from({ length: 16 }, (_, i) => ({
    number: i + 1,
    hasProblem: false,
  }))
);

const lowerTeeth = reactive<Tooth[]>(
  Array.from({ length: 16 }, (_, i) => ({
    number: i + 17,
    hasProblem: false,
  }))
);

// Computed
const hasSelectedTeeth = computed(() =>
  [...upperTeeth, ...lowerTeeth].some((tooth) => tooth.hasProblem)
);

const isFormValid = computed(
  () =>
    patientName.value &&
    patientId.value &&
    hasSelectedTeeth.value &&
    selectedProblem.value &&
    selectedTreatment.value
);

// Methods
const selectTooth = (tooth: Tooth) => {
  tooth.hasProblem = !tooth.hasProblem;
  selectedTooth.value = tooth.hasProblem ? tooth : null;
};

const resetForm = () => {
  patientName.value = "";
  patientId.value = "";
  treatmentDate.value = new Date().toISOString().substr(0, 10);
  notes.value = "";
  selectedProblem.value = null;
  selectedTreatment.value = null;
  upperTeeth.forEach((tooth) => (tooth.hasProblem = false));
  lowerTeeth.forEach((tooth) => (tooth.hasProblem = false));
};

const saveTreatment = async () => {
  if (!isFormValid.value) {
    return;
  }

  try {
    // TODO: Implement API call
    const treatmentData = {
      patientName: patientName.value,
      patientId: patientId.value,
      treatmentDate: treatmentDate.value,
      selectedTeeth: [...upperTeeth, ...lowerTeeth].filter((t) => t.hasProblem),
      problem: selectedProblem.value,
      treatment: selectedTreatment.value,
      notes: notes.value,
    };

    console.log("Saving treatment...", treatmentData);
    // await $inertia.post('/treatments', treatmentData);
  } catch (error) {
    console.error("Error saving treatment:", error);
  }
};
</script>

<style scoped>
.dental-chart {
  min-height: 400px;
}

.dental-grid {
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
  background-color: rgba(0, 0, 0, 0.05);
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
