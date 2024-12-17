<script setup lang="ts">
import { defineAsyncComponent, ref, watch, computed } from "vue";
import { Patient } from "../../types/Patient";
import { Header } from "../../types/TableHeader";
import { router, useForm, usePage } from "@inertiajs/vue3";
import SvgIcon from "../../Components/SvgIcon.vue";
import { useToast, POSITION } from "vue-toastification";

const icon = defineAsyncComponent(() => import("../../assets/svgs/PatientIcon.svg"));

const inertiaPage = usePage<{ flash: { message: string; error: string } }>();
const toast = useToast();
const patient = ref<Patient>();
const dialog = ref<boolean>(false);
const dialogDelete = ref<boolean>(false);
const isEdit = ref<boolean>(false);
const search = ref<string>();
const headers = ref<Header[]>([
  {
    title: "#",
    align: "start",
    key: "num",
    sortable: false,
    maxWidth: "50",
  },
  {
    title: "اسم المريض",
    align: "start",
    key: "name",
    minWidth: "180",
  },
  {
    title: "العمر",
    align: "start",
    key: "age",
    minWidth: "80",
  },
  {
    title: "الجنس",
    align: "start",
    key: "gender",
    minWidth: "80",
  },
  {
    title: "رقم البطاقة",
    align: "start",
    key: "identification_no",
    minWidth: "100",
  },
  {
    title: "الهاتف الأول",
    align: "center",
    key: "phone_1",
    minWidth: "100",
  },
  {
    title: "العنوان",
    align: "start",
    key: "address",
    minWidth: "100",
  },
  {
    title: "الإجرائات",
    align: "start",
    key: "actions",
    sortable: false,
    minWidth: "100",
  },
]);

const props = defineProps(["patients", "accounts"]);

const form = useForm({
  id: props.patients.id,
  name: props.patients.name,
  age: props.patients.age,
  gender: props.patients.gender,
  identification_no: props.patients.identification_no,
  phone_1: props.patients.phone_1,
  phone_2: props.patients.phone_2,
  address: props.patients.address,
  account_id: props.patients.account_id,
  note: props.patients.note,
});

const itemPerPageOption = [
  { value: 5, title: "5" },
  { value: 10, title: "10" },
  { value: 20, title: "20" },
  { value: 30, title: "30" },
  { value: -1, title: "$vuetify.dataFooter.itemsPerPageAll" },
];

const genders = ref([
  { value: "M", title: "ذكر" },
  { value: "F", title: "انثى" },
]);

const getFormTitle = () => {
  return isEdit.value ? "تعديل بيانات المريض" : "إضافة مريض جديد";
};

watch(dialog, (val) => {
  val || closeDialog();
});

watch(dialogDelete, (val) => {
  val || closeDeleteDialog();
});

const formInit = () => {
  form.reset();
  form.gender = "M";
  isEdit.value = false;
};

const editPatient = (item: Patient) => {
  form.id = item.id;
  form.name = item.name;
  form.age = item.age;
  form.gender = item.gender;
  form.address = item.address;
  form.account_id = item.account_id;
  form.identification_no = item.identification_no;
  form.phone_1 = item.phone_1;
  form.phone_2 = item.phone_2;
  form.note = item.note;

  patient.value = item;

  isEdit.value = true;
  dialog.value = true;
};

const DeletePatient = (item: Patient) => {
  dialogDelete.value = true;

  form.id = item.id;
  form.name = item.name;

  patient.value = item;
};

const onSuccess = () => {
  toast.success(inertiaPage.props.flash.message, {
    position: POSITION.TOP_RIGHT,
    timeout: 10000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    closeButton: "button",
    icon: true,
    rtl: false,
  });
};

const onError = () => {
  toast.error(inertiaPage.props.flash.error, {
    position: POSITION.TOP_RIGHT,
    timeout: 10000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    closeButton: "button",
    icon: true,
    rtl: false,
  });
};

const deleteItemConfirm = () => {
  form.delete(route("patient.delete", form.id), {
    onSuccess: () => {
      form.reset();
      closeDeleteDialog();
    },
    onError: () => {
      onError();
    },
  });
};

const closeDialog = () => {
  dialog.value = false;
};

const closeDeleteDialog = () => {
  dialogDelete.value = false;
};

const savePatient = () => {
  const genderCode = (gender: string) => (gender === "ذكر" ? "M" : "F");

  const handleSuccess = () => {
    form.reset();
    closeDialog();
    onSuccess();
  };

  if (isEdit.value) {
    form.patch(route("patient.update", form.id), {
      onSuccess: () => {
        isEdit.value = false;
        handleSuccess();
      },
    });
  } else {
    form.post(route("patient.store"), {
      onSuccess: handleSuccess,
      onError: () => {
        onError();
      },
    });
  }
};

const getGenderIcon = (gender: string) => {
  return gender === "M" ? "mdi-gender-male" : "mdi-gender-female";
};

const getGenderColor = (gender: string) => {
  return gender === "M" ? "blue" : "pink";
};

const formatPhoneNumber = (phone: string) => {
  return phone ? phone.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3") : "لا يوجد";
};

const showPatient = (patient: Patient) => {
  router.visit(route("patient.show", patient.id), {
    method: "get",
    preserveState: true,
  });
};

const itemsPerPage = ref(10);
const currentPage = ref(1);
</script>

<template>
  <v-card max-width="90%" class="mx-auto mt-16" elevation="4">
    <v-card-title
      class="d-flex align-center justify-space-between text-grey-darken-4 text-h5 font-weight-bold"
    >
      <div class="d-flex align-center justify-center">
        <SvgIcon name="PatientIcon" />
        &nbsp; بيانات المرضى
      </div>
    </v-card-title>

    <v-divider></v-divider>

    <v-toolbar flat class="bg-white border-t border-b">
      <v-text-input
        class="mr-5"
        v-model="search"
        density="compact"
        label="بحث"
        prepend-inner-icon="mdi-magnify"
        flat
        hide-details
      ></v-text-input>

      <v-spacer></v-spacer>

      <v-dialog v-model="dialog" max-width="60%" persistent>
        <template v-slot:activator="{ props }">
          <VBtnAdd v-bind="props" @click="formInit"> إضافة مريض جديد </VBtnAdd>
        </template>

        <v-card>
          <v-card-title class="d-flex justify-space-between bg-indigo-lighten-5">
            <span class="text-h5 text-grey-darken-4">{{ getFormTitle() }}</span>
            <v-btn
              icon="mdi-close"
              color="red"
              variant="plain"
              @click="closeDialog"
            ></v-btn>
          </v-card-title>

          <v-form @submit.prevent="savePatient">
            <v-card-text>
              <v-row>
                <v-col cols="12" md="6" sm="12">
                  <v-text-input
                    v-model="form.name"
                    label="اسم المريض"
                    :rules="[(v) => !!v || 'اسم المريض مطلوب']"
                    required
                  ></v-text-input>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-text-input
                    v-model="form.age"
                    label="العمر"
                    type="number"
                    :rules="[
                      (v) => !!v || 'العمر مطلوب',
                      (v) => v > 0 || 'العمر يجب أن يكون أكبر من 0',
                    ]"
                    required
                  ></v-text-input>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-select
                    v-model="form.gender"
                    :items="genders"
                    item-title="title"
                    item-value="value"
                    label="الجنس"
                    :rules="[(v) => !!v || 'الجنس مطلوب']"
                    required
                  ></v-select>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-text-input
                    v-model="form.identification_no"
                    label="رقم البطاقة"
                    :rules="[(v) => !!v || 'رقم البطاقة مطلوب']"
                    required
                  ></v-text-input>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-text-input
                    v-model="form.phone_1"
                    label="الهاتف الأول"
                    :rules="[(v) => !!v || 'الهاتف الأول مطلوب']"
                    required
                  ></v-text-input>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-text-input
                    v-model="form.phone_2"
                    label="الهاتف الثاني"
                  ></v-text-input>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-text-input v-model="form.address" label="العنوان"></v-text-input>
                </v-col>

                <v-col cols="12" md="6" sm="12">
                  <v-autocomplete
                    v-model="form.account_id"
                    :items="accounts"
                    item-title="name"
                    item-value="id"
                    label="الحساب"
                    no-data-text="لا توجد حسابات"
                    clearable
                  ></v-autocomplete>
                </v-col>

                <v-col cols="12">
                  <v-textarea
                    v-model="form.note"
                    label="ملاحظات"
                    auto-grow
                    rows="3"
                  ></v-textarea>
                </v-col>
              </v-row>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <VBtnCancel @click="closeDialog">إلغاء</VBtnCancel>
              <VBtnSave type="submit" :disabled="form.processing">حفظ</VBtnSave>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-dialog>

      <!-- Delete Dialog -->
      <v-dialog
        v-model="dialogDelete"
        max-width="400px"
        transition="dialog-bottom-transition"
      >
        <v-card class="rounded-lg">
          <v-card-title class="text-h5 d-flex align-center pa-4 bg-red-lighten-5">
            <v-icon
              icon="mdi-trash-can"
              color="red-darken-1"
              size="28"
              class="me-2"
            ></v-icon>
            تأكيد الحذف
          </v-card-title>

          <v-card-text class="pa-4 text-body-1">
            <div class="d-flex align-center mb-3">هل أنت متأكد من حذف هذا المريض؟</div>
            <div class="text-grey-darken-1 text-body-2">
              لا يمكن التراجع عن هذا الإجراء.
            </div>
          </v-card-text>

          <v-card-actions class="pa-4 pt-0">
            <v-spacer></v-spacer>
            <v-btn
              color="grey-darken-1"
              variant="text"
              @click="closeDeleteDialog"
              class="text-capitalize"
              prepend-icon="mdi-close"
            >
              إلغاء
            </v-btn>
            <v-btn
              color="red-darken-1"
              variant="elevated"
              @click="deleteItemConfirm"
              class="text-capitalize"
              prepend-icon="mdi-delete"
            >
              حذف
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>

    <v-data-table
      :headers="headers"
      :items="patients"
      :search="search"
      class="elevation-1 mt-4"
      :items-per-page="itemsPerPage"
      :page="currentPage"
      @update:page="currentPage = $event"
      :items-per-page-options="itemPerPageOption"
      show-current-page
      items-per-page-text="عدد الصفوف"
      color="indigo-lighten-5"
    >
      <template v-slot:item.num="{ index }">
        {{ index + 1 }}
      </template>

      <template v-slot:item.name="{ item }">
        <div class="d-flex align-center">
          <v-icon
            :color="getGenderColor((item as Patient)?.gender ?? '')"
            :icon="getGenderIcon((item as Patient)?.gender ?? '')"
            class="me-2"
          ></v-icon>
          <span>{{ (item as Patient).name }}</span>
        </div>
      </template>

      <template v-slot:item.gender="{ item }">
        <v-chip
          size="small"
          :color="(item as Patient).gender  === 'M' ? 'blue' : 'pink'"
          class="ml-2"
        >
          {{ (item as Patient).gender === "M" ? "ذكر" : "أنثى" }}
        </v-chip>
      </template>

      <template v-slot:item.actions="{ item }">
        <div class="d-flex gap-2">
          <v-icon color="primary" class="me-4" @click.stop="editPatient(item as Patient)">
            mdi-pencil
          </v-icon>

          <v-icon
            class="me-4"
            icon="mdi-delete"
            size="small"
            color="red-darken-1"
            @click="DeletePatient(item as Patient)"
          >
          </v-icon>

          <v-icon
            icon="mdi-eye"
            size="small"
            color="primary"
            @click="showPatient(item as Patient)"
          >
          </v-icon>
        </div>
      </template>

      <template v-slot:no-data>
        <div class="font-weight-bold text-grey-darken-1">لايوجد مرضى</div>
      </template>
    </v-data-table>

    <!-- <v-row justify="center" class="mt-4">
      <v-col cols="auto">
        <v-pagination
          v-model="currentPage"
          :length="totalPages"
          :total-visible="7"
          rounded="circle"
        ></v-pagination>
      </v-col>
    </v-row> -->
  </v-card>
</template>
