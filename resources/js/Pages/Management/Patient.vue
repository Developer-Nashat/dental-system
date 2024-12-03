<script setup lang="ts">
import { defineAsyncComponent, ref, watch } from "vue";
import { Patient } from "../../types/Patient";
import { Header } from "../../types/TableHeader";
import { useForm, usePage } from "@inertiajs/vue3";
import SvgIcon from "../../Components/SvgIcon.vue";
import { useToast, POSITION } from "vue-toastification";

const icon = defineAsyncComponent(
    () => import("../../assets/svgs/PatientIcon.svg")
);

const inertiaPage = usePage<{ flash: { message: string; error: string } }>();
const toast = useToast();
const patient = ref<Patient>();
const dialog = ref<boolean>(false);
const dialogDelete = ref<boolean>(false);
const isEdit = ref<boolean>(false);
const page = ref<number>(1);
const search = ref<string>();
const accountSearch = ref<string>();
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
        title: "رقم الحساب",
        align: "center",
        key: "account_id",
        minWidth: "100",
    },
    {
        title: "رقم البطاقة",
        align: "start",
        key: "identification_no",
        minWidth: "120",
    },
    {
        title: "العنوان",
        align: "start",
        key: "address",
        minWidth: "100",
    },
    {
        title: "الهاتف الأول",
        align: "center",
        key: "phone_1",
        minWidth: "100",
    },
    {
        title: "الهاتف الثاني",
        align: "center",
        key: "phone_2",
        minWidth: "100",
    },
    {
        title: "الملاحظات",
        align: "start",
        key: "note",
        maxWidth: "100",
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

    // form.patient = item;

    patient.value = item;

    isEdit.value = true;
    dialog.value = true;
};

const DeletePatient = (item: Patient) => {
    dialogDelete.value = true;

    form.id = item.id;
    form.name = item.name;

    // patient.value = item;
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

    // const formData = form.transform((data) => ({
    //     ...data,
    //     gender: genderCode(data.gender),
    // }));

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
</script>

<template>
    <v-card max-width="90%" class="mx-auto mt-16" elevation="4">
        <v-card-title
            class="d-flex align-center pe-2 text-grey-darken-4 mb-2 text-h5 font-weight-bold"
        >
            <SvgIcon name="PatientIcon" />
            &nbsp; بيانات المرضى

            <v-spacer></v-spacer>
        </v-card-title>

        <v-divider></v-divider>
        <v-data-table
            height="340"
            :headers="headers"
            :items="patients"
            :items-per-page="5"
            :items-per-page-options="itemPerPageOption"
            v-model:page="page"
            show-current-page
            items-per-page-text="عدد الصفوف"
            color="indigo-lighten-5"
            :sort-by="[{ key: 'name', order: 'asc' }]"
            multi-sort
            :search="search"
            fixed-header
            sticky
        >
            <template v-slot:item.num="{ index }">
                {{ index + 1 }}
            </template>

            <template v-slot:item.gender="{ value }">
                {{ value == "M" ? "ذكر" : "أنثى" }}
            </template>

            <template v-slot:top>
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
                            <VBtnAdd v-bind="props" @click="formInit">
                                إضافة جديد
                            </VBtnAdd>
                        </template>

                        <v-card>
                            <v-card-title
                                class="d-flex justify-space-between bg-indigo-lighten-5"
                            >
                                <span class="text-h5 text-grey-darken-4">{{
                                    getFormTitle()
                                }}</span>
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
                                        <v-col cols="12" md="10" sm="12">
                                            <v-text-input
                                                v-model="form.name"
                                                label="اسم المريض"
                                                color="primary"
                                                variant="outlined"
                                                :rules="[
                                                    (v: string) => !!v || 'يجب ان تدخل اسم المريض على الاقل',
                                                ]"
                                            ></v-text-input>
                                        </v-col>

                                        <v-col cols="12" md="2" sm="12">
                                            <v-select
                                                v-model="form.gender"
                                                :items="genders"
                                                item-title="title"
                                                item-value="value"
                                                label="الجنس"
                                            ></v-select>
                                        </v-col>

                                        <v-col cols="12" md="2" sm="12">
                                            <v-text-input
                                                v-model="form.age"
                                                label="العمر"
                                                type="number"
                                                hide-spin-buttons
                                            ></v-text-input>
                                        </v-col>
                                        <v-col cols="12" md="10" sm="12">
                                            <v-autocomplete
                                                v-model="form.account_id"
                                                v-model:search="accountSearch"
                                                :items="accounts"
                                                item-title="name"
                                                item-value="account_id"
                                                no-data-text="لاتوجد حسابات خاصة بالمرضى"
                                                label="الحساب"
                                                clearable
                                            ></v-autocomplete>
                                        </v-col>

                                        <v-col cols="12" md="6" sm="12">
                                            <v-text-input
                                                v-model="form.identification_no"
                                                label="رقم البطاقة"
                                            ></v-text-input>
                                        </v-col>

                                        <v-col cols="12" md="3" sm="12">
                                            <v-text-input
                                                v-model="form.phone_1"
                                                label="رقم الهاتف الأول"
                                                type="number"
                                                hide-spin-buttons
                                            ></v-text-input>
                                        </v-col>

                                        <v-col cols="12" md="3" sm="12">
                                            <v-text-input
                                                v-model="form.phone_2"
                                                label="رقم الهاتف الثاني"
                                                type="number"
                                                hide-spin-buttons
                                            ></v-text-input>
                                        </v-col>

                                        <v-col cols="12" md="12" sm="12">
                                            <v-text-input
                                                v-model="form.note"
                                                label="الملاحظة"
                                            ></v-text-input>
                                        </v-col>
                                    </v-row>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn-cancel @click="closeDialog">
                                        إلغاء
                                    </v-btn-cancel>
                                    <v-btn-save
                                        type="submit"
                                        :disabled="form.processing"
                                    >
                                        حفظ
                                    </v-btn-save>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>

                    <v-dialog
                        v-model="dialogDelete"
                        transition="dialog-top-transition"
                        max-width="600px"
                    >
                        <v-card>
                            <v-form
                                @submit.prevent="deleteItemConfirm"
                                method="delete"
                            >
                                <v-card-title
                                    class="text-h5 pb-8 my-4 text-center"
                                    height="8"
                                >
                                    <v-icon color="red-darken-1" size="100"
                                        >mdi-help-circle-outline</v-icon
                                    >
                                </v-card-title>
                                <v-card-text
                                    class="text-grey-darken-4 text-center mb-4"
                                >
                                    <span class="font-weight-bold text-h6"
                                        >هل انت متاكد من انك تريد حذف المريض
                                    </span>
                                    <span
                                        class="font-weight-bold text-h6 text-indigo-lighten-1"
                                    >
                                        '{{ form?.name }}'
                                    </span>
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-actions class="my-1">
                                    <v-spacer></v-spacer>
                                    <v-btn-cancel
                                        variant="text"
                                        @click="closeDeleteDialog"
                                        >إلغاء</v-btn-cancel
                                    >
                                    <v-btn-delete
                                        class="mr-12"
                                        type="submit"
                                        :disabled="form.processing"
                                        >نعم
                                        <v-icon class="pr-4 pt-1" size="large"
                                            >mdi-delete</v-icon
                                        >
                                    </v-btn-delete>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon
                    color="primary"
                    class="me-4"
                    @click="editPatient(item as Patient)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    @click="DeletePatient(item as Patient)"
                    color="red-darken-1"
                    hint="حذف"
                >
                    mdi-delete
                </v-icon>
            </template>

            <template v-slot:no-data>
                <div class="font-weight-bold text-grey-darken-1">
                    لايوجد مرضى
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
