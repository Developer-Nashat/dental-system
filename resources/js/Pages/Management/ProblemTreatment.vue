<template>
  <v-container fluid>
    <!-- Page Header -->
    <v-row>
      <v-col cols="12">
        <div class="d-flex align-center justify-space-between mb-6">
          <h1 class="text-h4 font-weight-bold">Problem Treatments</h1>
          <v-btn color="primary" prepend-icon="mdi-plus" @click="dialog = true">
            New Treatment
          </v-btn>
        </div>
      </v-col>
    </v-row>

    <!-- Filters -->
    <v-row>
      <v-col cols="12" sm="3">
        <v-select
          v-model="filters.status"
          :items="['all', 'pending', 'in_progress', 'treated']"
          label="Status"
          hide-details
          density="comfortable"
        ></v-select>
      </v-col>
      <v-col cols="12" sm="3">
        <v-select
          v-model="filters.visit"
          :items="sortedVisits"
          item-title="date"
          item-value="id"
          label="Visit Date"
          hide-details
          density="comfortable"
        ></v-select>
      </v-col>
      <v-col cols="12" sm="3">
        <v-select
          v-model="filters.problem"
          :items="props.problems"
          item-title="name"
          item-value="id"
          label="Problem"
          hide-details
          density="comfortable"
        ></v-select>
      </v-col>
      <v-col cols="12" sm="3">
        <v-select
          v-model="filters.treatment"
          :items="props.treatments"
          item-title="name"
          item-value="id"
          label="Treatment"
          hide-details
          density="comfortable"
        ></v-select>
      </v-col>
      <v-col cols="12" sm="3">
        <v-text-field
          v-model="filters.search"
          label="Search"
          prepend-inner-icon="mdi-magnify"
          density="comfortable"
          hide-details
          clearable
        ></v-text-field>
      </v-col>
    </v-row>

    <!-- Treatment Cards -->
    <v-row class="mt-4">
      <v-col v-for="item in filteredTreatments" :key="item.id" cols="12" md="6" lg="4">
        <v-card :class="{ 'border-treated': item.status === 'treated' }">
          <v-card-item>
            <template v-slot:prepend>
              <v-icon size="32" :color="getStatusColor(item.status)">
                {{ getStatusIcon(item.status) }}
              </v-icon>
            </template>

            <template v-slot:append>
              <v-chip :color="getStatusColor(item.status)" size="small">
                {{ formatStatus(item.status) }}
              </v-chip>
            </template>
          </v-card-item>

          <v-card-text>
            <v-list density="compact">
              <v-list-item>
                <template v-slot:prepend>
                  <v-icon color="primary" size="small">mdi-tooth</v-icon>
                </template>
                <v-list-item-title class="text-subtitle-2">Tooth</v-list-item-title>
                <v-list-item-subtitle>
                  {{ item.tooth_id }} - {{ item.tooth_id }}
                </v-list-item-subtitle>
              </v-list-item>

              <v-list-item>
                <template v-slot:prepend>
                  <v-icon color="error" size="small">mdi-alert-circle</v-icon>
                </template>
                <v-list-item-title class="text-subtitle-2">Problem</v-list-item-title>
                <v-list-item-subtitle>{{ item.problem_id }}</v-list-item-subtitle>
              </v-list-item>

              <v-list-item>
                <template v-slot:prepend>
                  <v-icon color="success" size="small">mdi-medical-bag</v-icon>
                </template>
                <v-list-item-title class="text-subtitle-2">Treatment</v-list-item-title>
                <v-list-item-subtitle>{{ item.treatment_id }}</v-list-item-subtitle>
              </v-list-item>

              <v-list-item v-if="item.notes">
                <template v-slot:prepend>
                  <v-icon color="info" size="small">mdi-note-text</v-icon>
                </template>
                <v-list-item-title class="text-subtitle-2">Notes</v-list-item-title>
                <v-list-item-subtitle>{{ item.notes }}</v-list-item-subtitle>
              </v-list-item>
            </v-list>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn variant="text" color="primary" @click="editTreatment(item)">
              Edit
            </v-btn>
            <v-btn
              variant="text"
              :color="item.status === 'treated' ? 'error' : 'success'"
              @click="toggleStatus(item)"
            >
              {{ item.status === "treated" ? "Mark Pending" : "Mark Treated" }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <!-- Empty State -->
      <v-col v-if="!filteredTreatments.length" cols="12">
        <v-card color="grey-lighten-4" class="d-flex flex-column align-center pa-12">
          <v-icon size="64" color="grey-darken-1" class="mb-4">
            mdi-tooth-outline
          </v-icon>
          <div class="text-h5 text-grey-darken-1 mb-2">No Treatments Found</div>
          <div class="text-body-1 text-medium-emphasis text-center">
            {{
              props.problemTreatments.length
                ? "Try adjusting your filters to see more treatments"
                : "Start by adding a new treatment"
            }}
          </div>
        </v-card>
      </v-col>
    </v-row>

    <!-- Add/Edit Dialog -->
    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title class="text-h5">
          {{ editingTreatment ? "Edit Treatment" : "New Treatment" }}
        </v-card-title>

        <v-card-text>
          <v-form ref="formRef" v-model="valid" @submit.prevent="handleSubmit">
            <v-row>
              <v-col cols="12" md="6">
                <v-select
                  v-model="form.visit_id"
                  :items="sortedVisits"
                  item-title="date"
                  item-value="id"
                  label="Visit Date*"
                  :rules="[(v) => !!v || 'Visit is required']"
                ></v-select>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="form.tooth_id"
                  :items="props.teeth"
                  item-title="number"
                  item-value="id"
                  label="Tooth Number*"
                  :rules="[(v) => !!v || 'Tooth is required']"
                ></v-select>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="form.problem_id"
                  :items="props.problems"
                  item-title="name"
                  item-value="id"
                  label="Problem*"
                  :rules="[(v) => !!v || 'Problem is required']"
                ></v-select>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="form.treatment_id"
                  :items="props.treatments"
                  item-title="name"
                  item-value="id"
                  label="Treatment*"
                  :rules="[(v) => !!v || 'Treatment is required']"
                ></v-select>
              </v-col>

              <v-col cols="12">
                <v-select
                  v-model="form.status"
                  :items="['pending', 'in_progress', 'treated']"
                  label="Status*"
                  :rules="[(v) => !!v || 'Status is required']"
                ></v-select>
              </v-col>

              <v-col cols="12">
                <v-textarea v-model="form.notes" label="Notes" rows="3"></v-textarea>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" variant="text" @click="handleDialogClose"> Cancel </v-btn>
          <v-btn
            color="primary"
            :loading="loading"
            :disabled="!valid"
            @click="handleSubmit"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Snackbar for notifications -->
    <v-snackbar
      v-model="snackbar.show"
      :color="snackbar.color"
      :timeout="3000"
      location="top"
    >
      {{ snackbar.text }}
      <template v-slot:actions>
        <v-btn variant="text" @click="snackbar.show = false"> Close </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { format } from "date-fns";
import { useForm } from "@inertiajs/vue3";

interface Tooth {
  id: number;
  number: string;
  name: string;
}

interface Problem {
  id: number;
  name: string;
}

interface Treatment {
  id: number;
  name: string;
}

interface Visit {
  id: string;
  date: string;
}

interface ProblemTreatment {
  id: string;
  visit_id: string;
  treatment_id: number;
  problem_id: number;
  tooth_id: number;
  notes: string | null;
  status: TreatmentStatus;
  visit: Visit;
  treatment: Treatment;
  problem: Problem;
  tooth: Tooth;
  created_at: string;
  updated_at: string;
}

interface Props {
  problemTreatments: ProblemTreatment[];
  visits: Visit[];
  treatments: Treatment[];
  problems: Problem[];
  teeth: Tooth[];
}

type TreatmentStatus = "pending" | "in_progress" | "treated";

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: "treatment-updated"): void;
  (e: "treatment-created"): void;
}>();

// State
const dialog = ref(false);
const formRef = ref<HTMLFormElement | null>(null);
const valid = ref(false);
const loading = ref(false);
const snackbar = ref({
  show: false,
  text: "",
  color: "success",
});

const initialFormState = {
  visit_id: "",
  treatment_id: null as number | null,
  problem_id: null as number | null,
  tooth_id: null as number | null,
  notes: "",
  status: "pending" as TreatmentStatus,
};

const form = ref({ ...initialFormState });
const editingTreatment = ref<ProblemTreatment | null>(null);
const filters = ref({
  status: "all",
  visit: null as string | null,
  problem: null as number | null,
  treatment: null as number | null,
  search: "" as string,
});

// Computed
const filteredTreatments = computed(() => {
  return props.problemTreatments.filter((item) => {
    const matchesStatus =
      filters.value.status === "all" || item.status === filters.value.status;
    const matchesVisit = !filters.value.visit || item.visit_id === filters.value.visit;
    const matchesProblem =
      !filters.value.problem || item.problem_id === filters.value.problem;
    const matchesTreatment =
      !filters.value.treatment || item.treatment_id === filters.value.treatment;
    const matchesSearch =
      !filters.value.search ||
      item.notes?.toLowerCase().includes(filters.value.search.toLowerCase()) ||
      item.problem.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
      item.treatment.name.toLowerCase().includes(filters.value.search.toLowerCase());

    return (
      matchesStatus && matchesVisit && matchesProblem && matchesTreatment && matchesSearch
    );
  });
});

// Methods
const formatDate = (date: string) => {
  return format(new Date(date), "MMM dd, yyyy");
};

const formatStatus = (status: string) => {
  return status.replace("_", " ").replace(/\b\w/g, (l) => l.toUpperCase());
};

const getStatusColor = (status: TreatmentStatus): string => {
  const statusColors = {
    treated: "success",
    in_progress: "info",
    pending: "warning",
  };
  return statusColors[status];
};

const getStatusIcon = (status: TreatmentStatus): string => {
  const statusIcons = {
    treated: "mdi-check-circle",
    in_progress: "mdi-progress-clock",
    pending: "mdi-clock-outline",
  };
  return statusIcons[status];
};

const resetForm = () => {
  form.value = { ...initialFormState };
  editingTreatment.value = null;
  if (formRef.value) {
    formRef.value.resetValidation();
  }
};

const showNotification = (text: string, color: "success" | "error" = "success") => {
  snackbar.value = {
    show: true,
    text,
    color,
  };
};

const handleDialogClose = () => {
  dialog.value = false;
  resetForm();
};

const editTreatment = (treatment: ProblemTreatment) => {
  editingTreatment.value = treatment;
  form.value = {
    visit_id: treatment.visit_id,
    treatment_id: treatment.treatment_id,
    problem_id: treatment.problem_id,
    tooth_id: treatment.tooth_id,
    notes: treatment.notes || "",
    status: treatment.status,
  };
  dialog.value = true;
};

const toggleStatus = async (treatment: ProblemTreatment) => {
  try {
    const form = useForm({
      status: treatment.status === "treated" ? "pending" : "treated",
    });

    await form.patch(route("problem-treatments.update", treatment.id), {
      preserveScroll: true,
    });

    showNotification("Treatment status updated successfully");
    emit("treatment-updated");
  } catch (error) {
    showNotification("Failed to update treatment status", "error");
  }
};

const handleSubmit = async () => {
  if (!formRef.value?.validate()) return;

  loading.value = true;
  const treatmentForm = useForm(form.value);

  try {
    if (editingTreatment.value) {
      await treatmentForm.patch(
        route("problem-treatments.update", editingTreatment.value.id),
        {
          preserveScroll: true,
        }
      );
      showNotification("Treatment updated successfully");
      emit("treatment-updated");
    } else {
      await treatmentForm.post(route("problem-treatments.store"), {
        preserveScroll: true,
      });
      showNotification("Treatment created successfully");
      emit("treatment-created");
    }
    handleDialogClose();
  } catch (error) {
    showNotification(
      `Failed to ${editingTreatment.value ? "update" : "create"} treatment`,
      "error"
    );
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.border-treated {
  border-left: 4px solid rgb(var(--v-theme-success));
}

.v-list-item-subtitle {
  white-space: normal !important;
}
</style>
