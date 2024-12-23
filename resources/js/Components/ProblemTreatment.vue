<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { Header } from "../types/TableHeader";
import { ProblemTreatment } from "../types/ProblemTreatment";
import { router, useForm, usePage } from "@inertiajs/vue3";
import SvgIcon from "./SvgIcon.vue";
import { useToast, POSITION } from "vue-toastification";
import { useDate } from "vuetify";
import { Tooth } from "../types/Tooth";
import DentalChart from "./DentalChart.vue";

const props = defineProps({
    problems: {
        type: Array,
        required: true,
    },
    treatments: {
        type: Array,
        required: true,
    },
    teeth: {
        type: Array as () => Tooth[],
        required: true,
    },
    ProblemTreatments: {
        type: Array as () => ProblemTreatment[],
        required: true,
    },
});

// defineProps(["problems", "treatments", "teeth"]);

const inertiaPage = usePage<{ flash: { message: string; error: string } }>();
const toast = useToast();
const problemTreatment = ref<ProblemTreatment>();
const dialog = ref<boolean>(false);
const dialogDelete = ref<boolean>(false);
const isEdit = ref<boolean>(false);
const page = ref<number>(1);
const minDate = ref(new Date().toISOString().slice(0, 10));

const show = onMounted(() => {
    console.log("Problem Treatment " + props.ProblemTreatments[0].visit_id);
});

const form = useForm<ProblemTreatment>({
    id: undefined,
    visit_id: props.ProblemTreatments[0].visit_id,
    problem_id: props.ProblemTreatments.problem_id,
    treatment_id: props.ProblemTreatments.treatment_id,
    tooth_id: props.ProblemTreatments.tooth_id,
});

const editProblemTreatment = (item: ProblemTreatment) => {
    form.id = item.id;
    form.visit_id = item.visit_id;
    form.problem_id = item.problem_id;
    form.treatment_id = item.treatment_id;
    form.tooth_id = item.tooth_id;

    problemTreatment.value = item;

    isEdit.value = true;
    dialog.value = true;
};

const deleteProblemTreatment = (item: ProblemTreatment) => {
    dialogDelete.value = true;

    form.id = item.id;
};

const emit = defineEmits(["updateProblemTreatment"]);

watch(props.ProblemTreatments, () => {
    emit("updateProblemTreatment", props.ProblemTreatments);
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

const deleteItemConfirm = () => {
    form.delete(route("problemTreatment.delete", form.id), {
        onSuccess: () => {
            onSuccess();
            form.reset();
            closeDeleteDialog();

            emit("updateProblemTreatment");
        },
        preserveScroll: true,

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

const saveProblemTreatment = () => {
    const handleSuccess = () => {
        form.reset();
        closeDialog();
        onSuccess();

        emit("updateProblemTreatment");
    };

    if (isEdit.value) {
        form.patch(route("problemTreatment.update", form.id), {
            onSuccess: () => {
                isEdit.value = false;
                handleSuccess();
            },
        });
    } else {
        form.post(route("problemTreatment.store"), {
            onSuccess: handleSuccess,
            onError: () => {
                onError();
            },
        });
    }
};

const formInit = () => {
    form.reset();
    isEdit.value = false;
};

const getFormTitle = () => {
    return isEdit.value ? "تعديل معالجة المشكلة" : "إضافة معالجة مشكلة جديدة";
};

watch(dialog, (val) => {
    val || closeDialog();
});

watch(dialogDelete, (val) => {
    val || closeDeleteDialog();
});

const headers = ref<Header[]>([
    {
        title: "#",
        align: "start",
        key: "num",
        sortable: false,
        maxWidth: "50",
    },
    {
        title: "المشكلة",
        align: "start",
        key: "problem",
        minWidth: "180",
    },
    {
        title: "المعالجة",
        align: "start",
        key: "treatment",
        minWidth: "180",
    },
    {
        title: "رقم السن ",
        align: "start",
        key: "tooth",
    },
    {
        title: "السعر",
        align: "start",
        key: "price",
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

const itemPerPageOption = [
    { value: 5, title: "5" },
    { value: 10, title: "10" },
    { value: 20, title: "20" },
    { value: 30, title: "30" },
    { value: -1, title: "$vuetify.dataFooter.itemsPerPageAll" },
];

const selectedTooth = (tooth: Tooth) => {
    if (tooth) form.tooth_id == tooth.id;
};
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
</script>

<template>
    <v-card class="mx-auto">
        <v-card-title class="d-flex align-center text-h5">
            المشاكل والعلاجات
        </v-card-title>

        <v-divider></v-divider>
        <v-data-table
            height="320"
            :headers="headers"
            :items="props.ProblemTreatments"
            :items-per-page="5"
            :items-per-page-options="itemPerPageOption"
            v-model:page="page"
            show-current-page
            items-per-page-text="عدد الصفوف"
            color="indigo-lighten-5"
            multi-sort
            sticky
        >
            <template v-slot:item.num="{ index }">
                {{ index + 1 }}
            </template>

            <template v-slot:item.problem="{ item }">
                {{ item.problem.name }}
            </template>

            <template v-slot:item.treatment="{ item }">
                {{ item.treatment.name }}
            </template>
            <template v-slot:item.tooth="{ item }">
                {{ item.tooth.tooth_number }}
            </template>

            <template v-slot:item.price="{ item }">
                {{ item.treatment.price }}
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon
                    color="primary"
                    class="me-4"
                    @click.stop="editProblemTreatment(item as ProblemTreatment)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    @click.stop="
                        deleteProblemTreatment(item as ProblemTreatment)
                    "
                    color="red-darken-1"
                    hint="حذف"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:top>
                <v-dialog v-model="dialog" max-width="60%" persistent>
                    <template v-slot:activator="{ props }">
                        <div class="d-flex align-center justify-end">
                            <VBtnAdd
                                min-width="120"
                                class="ma-4"
                                v-bind="props"
                                @click="formInit"
                            >
                                إضافة
                            </VBtnAdd>
                        </div>
                    </template>

                    <v-card>
                        <v-card-title
                            sticky
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

                        <v-form @submit.prevent="saveProblemTreatment">
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="10" sm="12">
                                        <v-autocomplete
                                            v-model="form.problem_id"
                                            label="المشكلة"
                                            :items="problems"
                                            item-title="name"
                                            item-value="id"
                                            no-data-text="لاتوجد بيانات خاصة بالمشكلات"
                                            clearable
                                        ></v-autocomplete>
                                    </v-col>

                                    <v-col cols="12" md="10" sm="12">
                                        <v-autocomplete
                                            v-model="form.treatment_id"
                                            label="اسم العلاج"
                                            :items="treatments"
                                            item-title="name"
                                            item-value="id"
                                            no-data-text="لاتوجد بيانات خاصة بالعلاجات"
                                            clearable
                                        ></v-autocomplete>
                                    </v-col>

                                    <v-col cols="12" md="10" sm="12">
                                        <!-- <v-autocomplete
                      v-model="form.tooth_id"
                      :items="teeth"
                      item-title="tooth_number"
                      item-value="id"
                      label="رقم السن"
                      clearable
                    ></v-autocomplete> -->

                                        <dental-chart :teeth="upperTeeth" />
                                        <dental-chart
                                            :teeth="lowerTeeth"
                                            :is-bottom="true"
                                        />
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
                    max-width="400px"
                    transition="dialog-bottom-transition"
                >
                    <v-card class="rounded-lg">
                        <v-card-title
                            class="text-h5 d-flex align-center pa-4 bg-red-lighten-5"
                        >
                            <v-icon
                                icon="mdi-trash-can"
                                color="red-darken-1"
                                size="28"
                                class="me-2"
                            ></v-icon>
                            تأكيد الحذف
                        </v-card-title>

                        <v-card-text class="pa-4 text-body-1">
                            <div class="d-flex align-center mb-3">
                                هل أنت متأكد من انك تريدالحذف؟
                            </div>
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
            </template>
        </v-data-table>
    </v-card>
</template>

<style scoped></style>
