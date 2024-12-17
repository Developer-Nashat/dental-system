<!-- PatientProfile.vue -->
<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Patient } from "../../types/Patient";
import { useForm } from "@inertiajs/vue3";
import moment from "moment";

interface Props {
  patient?: Patient;
  visits?: any[];
}

const props = defineProps<Props>();
const activeSection = ref('personal-info');
const loading = ref(false);
const visits = ref(props.visits || []);

const form = useForm({
  id: props.patient?.id || 0,
  name: props.patient?.name || "",
  gender: props.patient?.gender || "M",
  identification_no: props.patient?.identification_no || "",
  address: props.patient?.address || "",
  phone_1: props.patient?.phone_1 || "",
  phone_2: props.patient?.phone_2 || "",
  note: props.patient?.note || "",
});

const visitHeaders = [
  { title: "Date", key: "visit_date" },
  { title: "Problems", key: "problems" },
  { title: "Treatments", key: "treatments" },
  { title: "Notes", key: "notes" },
  { title: "Actions", key: "actions", sortable: false },
];

const navigationItems = [
  { 
    id: 'personal-info',
    label: 'Personal Information',
    icon: 'mdi-account-details',
  },
  {
    id: 'visits',
    label: 'Visit History',
    icon: 'mdi-calendar-clock',
  },
];

const formatDate = (date: string) => {
  return moment(date).format("MMMM D, YYYY");
};

const getGenderIcon = (gender: string) => {
  return gender === "M" ? "mdi-gender-male" : "mdi-gender-female";
};

const getGenderColor = (gender: string) => {
  return gender === "M" ? "blue" : "pink";
};

const saveProfile = () => {
  form.put(route("patients.update", form.id), {
    onSuccess: () => {
      // Show success message
    },
  });
};
</script>

<template>
  <div class="patient-profile">
    <!-- Header -->
    <div class="profile-header mb-6">
      <div class="d-flex align-center">
        <v-avatar color="grey-lighten-3" size="96" class="me-4">
          <v-icon
            :color="getGenderColor(form.gender)"
            :icon="getGenderIcon(form.gender)"
            size="48"
          ></v-icon>
        </v-avatar>
        <div>
          <h1 class="text-h4 mb-1">{{ form.name }}</h1>
          <div class="text-subtitle-1 text-medium-emphasis">
            ID: {{ form.identification_no }}
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="d-flex profile-content">
      <!-- Sidebar Navigation -->
      <div class="navigation-sidebar">
        <v-card flat class="navigation-card">
          <v-list>
            <v-list-item
              v-for="item in navigationItems"
              :key="item.id"
              :value="item.id"
              :active="activeSection === item.id"
              @click="activeSection = item.id"
              class="navigation-item"
              :class="{ 'active-item': activeSection === item.id }"
            >
              <template v-slot:prepend>
                <v-icon :icon="item.icon"></v-icon>
              </template>
              <v-list-item-title>{{ item.label }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-card>
      </div>

      <!-- Content Area -->
      <div class="content-area">
        <!-- Personal Information Section -->
        <v-card v-show="activeSection === 'personal-info'" flat class="content-card">
          <v-card-text>
            <v-form @submit.prevent="saveProfile">
              <div class="section-header mb-6">
                <h2 class="text-h5 font-weight-medium">Personal Information</h2>
                <p class="text-subtitle-1 text-medium-emphasis mt-1">
                  Update your personal information and contact details
                </p>
              </div>

              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.name"
                    label="Full Name"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-account"
                    :error-messages="form.errors.name"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    v-model="form.gender"
                    label="Gender"
                    :items="[
                      { title: 'Male', value: 'M' },
                      { title: 'Female', value: 'F' },
                    ]"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-gender-male-female"
                    :error-messages="form.errors.gender"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.identification_no"
                    label="ID Number"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-card-account-details"
                    :error-messages="form.errors.identification_no"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.phone_1"
                    label="Primary Phone"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-phone"
                    :error-messages="form.errors.phone_1"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.phone_2"
                    label="Secondary Phone"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-phone-plus"
                    :error-messages="form.errors.phone_2"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-textarea
                    v-model="form.address"
                    label="Address"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-map-marker"
                    :error-messages="form.errors.address"
                    rows="3"
                  ></v-textarea>
                </v-col>
                <v-col cols="12">
                  <v-textarea
                    v-model="form.note"
                    label="Notes"
                    variant="outlined"
                    density="comfortable"
                    prepend-inner-icon="mdi-note-text"
                    :error-messages="form.errors.note"
                    rows="3"
                  ></v-textarea>
                </v-col>
              </v-row>

              <v-divider class="my-6"></v-divider>

              <div class="d-flex justify-end">
                <v-btn
                  color="primary"
                  type="submit"
                  :loading="form.processing"
                  :disabled="form.processing"
                  size="large"
                >
                  <v-icon start icon="mdi-content-save"></v-icon>
                  Save Changes
                </v-btn>
              </div>
            </v-form>
          </v-card-text>
        </v-card>

        <!-- Visit History Section -->
        <v-card v-show="activeSection === 'visits'" flat class="content-card">
          <v-card-text>
            <div class="section-header mb-6">
              <div class="d-flex justify-space-between align-center">
                <div>
                  <h2 class="text-h5 font-weight-medium">Visit History</h2>
                  <p class="text-subtitle-1 text-medium-emphasis mt-1">
                    View and manage patient visits
                  </p>
                </div>
                <v-btn
                  color="primary"
                  prepend-icon="mdi-plus"
                  size="large"
                >
                  New Visit
                </v-btn>
              </div>
            </div>

            <v-data-table
              :headers="visitHeaders"
              :items="visits"
              :loading="loading"
              class="elevation-0 visits-table"
            >
              <template v-slot:item.visit_date="{ item }">
                {{ formatDate(item.visit_date) }}
              </template>
              
              <template v-slot:item.actions="{ item }">
                <v-btn
                  icon
                  variant="text"
                  size="small"
                  color="primary"
                  :to="route('visits.show', item.id)"
                >
                  <v-icon>mdi-eye</v-icon>
                </v-btn>
                <v-btn
                  icon
                  variant="text"
                  size="small"
                  color="primary"
                  :to="route('visits.edit', item.id)"
                >
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </div>
    </div>
  </div>
</template>

<style scoped>
.patient-profile {
  max-width: 1400px;
  margin: 0 auto;
  padding: 24px;
}

.profile-header {
  background: white;
  padding: 24px;
  border-radius: 8px;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.profile-content {
  gap: 24px;
}

.navigation-sidebar {
  width: 280px;
  flex-shrink: 0;
}

.navigation-card {
  position: sticky;
  top: 24px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.navigation-item {
  border-radius: 8px;
  margin: 4px 8px;
}

.active-item {
  background-color: rgb(var(--v-theme-primary));
  color: white;
}

.active-item :deep(.v-icon) {
  color: white;
}

.content-area {
  flex: 1;
}

.content-card {
  background: white;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.section-header {
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 16px;
}

.visits-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
}

:deep(.v-data-table) {
  background: transparent !important;
}

:deep(.v-data-table-header) {
  background: #f9fafb;
}

:deep(.v-data-table-header th) {
  font-weight: 600 !important;
  text-transform: uppercase;
  font-size: 0.75rem !important;
  letter-spacing: 0.05em;
}
</style>
