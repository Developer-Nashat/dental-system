<script setup lang="ts">
import { ref, watch } from "vue";
import { Treatment } from "../../types/Treatment";
import { Header } from "../../types/TableHeader";
import { useForm, usePage } from "@inertiajs/vue3";
import SvgIcon from "../../Components/SvgIcon.vue";
import { useToast, POSITION } from "vue-toastification";

const inertiaPage = usePage<{ flash: { message: string; error: string } }>();
const toast = useToast();
const treatment = ref<Treatment>();
const dialog = ref<boolean>(false);
const dialogDelete = ref<boolean>(false);
const isEdit = ref<boolean>(false);
const page = ref<number>(1);
const treatmentsPerPage = ref<number>(5);
const search = ref<string | undefined>();
const headers = ref<Header[]>([
    {
        title: "#",
        align: "start",
        key: "num",
        sortable: false,
        maxWidth: "50",
    },
    {
        title: "اسم المعالجة",
        align: "start",
        key: "name",
        minWidth: "180",
    },
    {
        title: "السعر",
        align: "start",
        key: "price",
        minWidth: "100",
    },
    {
        title: "الوصف",
        align: "start",
        key: "description",
    },
    {
        title: "الإجرائات",
        align: "start",
        key: "actions",
        sortable: false,
        minWidth: "100",
    },
]);

const props = defineProps(["treatments"]);

const form = useForm({
    id: props.treatments.id,
    name: props.treatments.name,
    price: props.treatments.price,
    description: props.treatments.description,
});

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

const itemPerPageOption = [
    { value: 5, title: "5" },
    { value: 10, title: "10" },
    { value: 20, title: "20" },
    { value: 30, title: "30" },
    { value: -1, title: "$vuetify.dataFooter.itemsPerPageAll" },
];

const itemPerPage = (val: any) => {
    console.log(val);
    // treatmentsPerPage.value = val;
};

const footerProps = { "items-per-page-options": [5, 10, 15, -1] };

const pageCount = () => {
    return props.treatments === null
        ? 0
        : Math.ceil(
              props.treatments.length / Math.abs(treatmentsPerPage.value)
          );
};
const pagePaginationText = () => {
    return (
        // page.value.toString() +
        // "-" +
        // pageCount().toString() +
        // " من " +
        // props.treatments.length
        "عدد المعالجات " + props.treatments.length
    );
};

const getFormTitle = () => {
    return isEdit.value ? "تعديل معالجة" : "إضافة معالجة جديدة";
};

watch(dialog, (val) => {
    val || closeDialog();
});

watch(dialogDelete, (val) => {
    val || closeDeleteDialog();
});

const editTreatment = (item: Treatment) => {
    form.id = item.id;
    form.name = item.name;
    form.price = item.price;
    form.description = item.description;

    treatment.value = item;

    isEdit.value = true;
    dialog.value = true;
};

const DeleteTreatment = (item: Treatment) => {
    form.id = item.id;
    form.name = item.name;

    treatment.value = item;

    dialogDelete.value = true;
};

const deleteItemConfirm = () => {
    form.delete(route("treatment.delete", treatment.value), {
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

const saveTreatment = () => {
    const handleSuccess = () => {
        form.reset();
        closeDialog();
        onSuccess();
    };

    if (!isEdit.value) {
        form.post(route("treatment.store"), {
            onSuccess: () => {
                handleSuccess();
            },
            onError: () => {
                onError();
            },
        });
    } else {
        form.patch(route("treatment.update", treatment.value), {
            onSuccess: () => {
                isEdit.value = false;
                handleSuccess();
            },
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
            class="d-flex align-center pe-2 text-primary mb-2 text-h5 font-weight-bold"
        >
            <v-icon icon="mdi-video-input-component"></v-icon> &nbsp; العلاجات

            <v-spacer></v-spacer>
        </v-card-title>

        <v-divider></v-divider>

        <v-data-table
            height="320"
            :headers="headers"
            :items="treatments"
            :items-per-page="5"
            :items-per-page-options="itemPerPageOption"
            v-model:page="page"
            show-current-page
            items-per-page-text="عدد الصفوف"
            color="indigo-lighten-5"
            :sort-by="[{ key: 'name', order: 'asc' }]"
            multi-sort
            :search="search"
            sticky
        >
            <template v-slot:item.num="{ item, index }">
                {{ index + 1 }}
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
                            <VBtnAdd
                                v-bind="props"
                                @click="
                                    {
                                        form.reset();
                                        isEdit = false;
                                    }
                                "
                            >
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

                            <v-form fast-fail @submit.prevent="saveTreatment">
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12" md="8" sm="12">
                                            <v-text-input
                                                v-model="form.name"
                                                label="اسم المعالجة"
                                                color="primary"
                                                variant="outlined"
                                            ></v-text-input>
                                        </v-col>

                                        <v-col cols="12" md="4" sm="12">
                                            <v-text-input
                                                v-model="form.price"
                                                label="السعر"
                                                variant="outlined"
                                                color="primary"
                                            ></v-text-input>
                                        </v-col>

                                        <v-col cols="12" md="12" sm="12">
                                            <v-text-input
                                                v-model="form.description"
                                                label="الوصف"
                                                color="primary"
                                                variant="outlined"
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
                                        >هل انت متاكد من انك تريد حذف المعالجة
                                    </span>
                                    <span
                                        class="font-weight-bold text-h6 text-indigo-lighten-1"
                                    >
                                        '{{ treatment?.name }}'</span
                                    >
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
                    @click="editTreatment(item as Treatment)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    @click="DeleteTreatment(item as Treatment)"
                    color="red-darken-1"
                    hint="حذف"
                >
                    mdi-delete
                </v-icon>
            </template>

            <template v-slot:no-data>
                <div class="font-weight-bold text-grey-darken-1">
                    لاتوجد بيانات للعلاجات
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
