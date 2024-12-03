<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import { Visit } from "../../types/Visit";
import { Header } from "../../types/TableHeader";
import { router, useForm, usePage } from "@inertiajs/vue3";
import SvgIcon from "../../Components/SvgIcon.vue";
import { useToast, POSITION } from "vue-toastification";
import { useDate } from "vuetify";

enum Period {
    YESTERDAY = "أمس",
    TODAY = "اليوم",
    TOMORROW = "غدا",
    THIS_WEEK = "الاسبوع الحالي",
    THIS_MONTH = "الشهر الحالي",
    CUSTOME = "نطاق مخصص",
}

const periods = [
    Period.YESTERDAY,
    Period.TODAY,
    Period.TOMORROW,
    Period.THIS_WEEK,
    Period.THIS_MONTH,
    Period.CUSTOME,
];

const inertiaPage = usePage<{ flash: { message: string; error: string } }>();
const toast = useToast();
const visit = ref<Visit>();
const dialog = ref<boolean>(false);
const dialogDelete = ref<boolean>(false);
const timeDialog = ref<boolean>(false);
const customDateDialog = ref<boolean>(false);
const isEdit = ref<boolean>(false);
const page = ref<number>(1);
const search = ref<string>();
const selection = ref<number>(1);
const minDate = ref(new Date().toISOString().slice(0, 10));
const selectedPeriod = ref<Period>(Period.TODAY);
const customDateRange = ref([]);
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
        key: "patient_id",
        minWidth: "180",
    },
    {
        title: "اسم الطبيب",
        align: "start",
        key: "dentist_id",
        minWidth: "180",
    },
    {
        title: "تاريخ الزيارة",
        align: "start",
        key: "visit_date",
        minWidth: "80",
    },
    {
        title: "وقت الزيارة",
        align: "start",
        key: "visit_time",
        minWidth: "80",
    },
    {
        title: "حالة الزيارة",
        align: "start",
        key: "status_id",
        minWidth: "80",
    },
    {
        title: "الإجرائات",
        align: "start",
        key: "actions",
        sortable: false,
        minWidth: "100",
    },
]);

const filterVisitsByPeriod = (period: Period) => {
    const today = new Date();
    const startOfDay = new Date(today.setHours(0, 0, 0, 0));
    const endOfDay = new Date(today.setHours(23, 59, 59, 999));

    switch (period) {
        case Period.TODAY:
            return props.visits.filter(
                (visit: Visit) =>
                    new Date(visit.visit_date) >= startOfDay &&
                    new Date(visit.visit_date) <= endOfDay
            );
        case Period.YESTERDAY:
            const yesterday = new Date(today.setDate(today.getDate() - 1));
            return props.visits.filter(
                (visit: Visit) =>
                    new Date(visit.visit_date) >=
                        new Date(yesterday.setHours(0, 0, 0, 0)) &&
                    new Date(visit.visit_date) <=
                        new Date(yesterday.setHours(23, 59, 59, 999))
            );
        case Period.TOMORROW:
            const tomorrow = new Date(today.setDate(today.getDate() + 1));
            return props.visits.filter(
                (visit: Visit) =>
                    new Date(visit.visit_date) >=
                        new Date(tomorrow.setHours(0, 0, 0, 0)) &&
                    new Date(visit.visit_date) <=
                        new Date(tomorrow.setHours(23, 59, 59, 999))
            );
        case Period.THIS_WEEK:
            const startOfWeek = new Date(
                today.setDate(today.getDate() - today.getDay())
            );
            const endOfWeek = new Date(
                today.setDate(startOfWeek.getDate() + 6)
            );
            return props.visits.filter(
                (visit: Visit) =>
                    new Date(visit.visit_date) >= startOfWeek &&
                    new Date(visit.visit_date) <= endOfWeek
            );
        case Period.THIS_MONTH:
            const startOfMonth = new Date(
                today.getFullYear(),
                today.getMonth(),
                1
            ); // أول يوم بالشهر
            const endOfMonth = new Date(
                today.getFullYear(),
                today.getMonth() + 1,
                0
            ); // آخر يوم بالشهر
            return props.visits.filter(
                (visit: Visit) =>
                    new Date(visit.visit_date) >= startOfMonth &&
                    new Date(visit.visit_date) <= endOfMonth
            );
        default:
            return props.visits;
    }
};

onMounted(() => {
    selectedPeriod.value = "TODAY" as Period;

    selection.value = Object.keys(Period).indexOf(selectedPeriod.value) ?? 0;

    router.visit("visits", {
        method: "get",
        data: {
            period: selectedPeriod.value,
        },
        preserveState: true,
    });
});

const getSelectedPeriod = (period: Period) => {
    selectedPeriod.value =
        (Object.entries(Period).find(
            ([k, v]) => v === period
        )?.[0] as Period) ?? Period.TODAY;

    customDateDialog.value = false;

    selection.value = periods.indexOf(period);

    const startDate = new Date(customDateRange?.value[0]);
    const endDate = new Date(
        customDateRange?.value[customDateRange?.value.length - 1]
    );

    router.visit("visits", {
        method: "get",
        data: {
            period: selectedPeriod.value,
            startDate: startDate.toDateString(),
            endDate: endDate.toDateString(),
        },
        preserveState: true,
    });
};

const props = defineProps(["visits", "dentists", "statuses", "patients"]);

const form = useForm({
    id: props.visits.id,
    patient_id: props.visits.patient_id,
    dentist_id: props.visits.dentist_id,
    status_id: props.visits.status_id,
    visit_date: new Date(props.visits.visit_date),
    visit_time: props.visits.visit_time,
    is_done: props.visits.is_done,
});

const itemPerPageOption = [
    { value: 5, title: "5" },
    { value: 10, title: "10" },
    { value: 20, title: "20" },
    { value: 30, title: "30" },
    { value: -1, title: "$vuetify.dataFooter.itemsPerPageAll" },
];

const getFormTitle = () => {
    return isEdit.value ? "تعديل بيانات الزيارة" : "تسجيل  زيارة جديدة";
};

const visitDate = computed({
    get: () => new Date(form.visit_date),
    set: (value: Date) => (form.visit_date = value),
});

watch(dialog, (val) => {
    val || closeDialog();
});

watch(dialogDelete, (val) => {
    val || closeDeleteDialog();
});

// watch(selectedPeriod, (period) => {
//     router.get("patients", { search: period }, { preserveState: true });
// });

// watch(customDateRange, (range) => {
//     console.log("Custome Date Range: ", range);
// });

const formInit = () => {
    form.reset();
    isEdit.value = false;
};

const editVisit = (item: Visit) => {
    console.log(item.dentist_id);

    form.id = item.id;
    form.dentist_id = item.dentist_id;
    form.patient_id = item.patient_id;
    form.status_id = item.status_id;
    form.is_done = item.is_done;
    form.visit_date = item.visit_date;
    form.visit_time = item.visit_time;

    visit.value = item;

    isEdit.value = true;
    dialog.value = true;
};

const deleteVisit = (item: Visit) => {
    dialogDelete.value = true;

    form.id = item.id;
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
    form.delete(route("visit.delete", form.id), {
        onSuccess: () => {
            onSuccess();
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

const saveVisit = () => {
    const handleSuccess = () => {
        form.reset();
        closeDialog();
        onSuccess();
    };

    if (isEdit.value) {
        form.patch(route("visit.update", form.id), {
            onSuccess: () => {
                isEdit.value = false;
                handleSuccess();
            },
        });
    } else {
        form.post(route("visit.store"), {
            onSuccess: handleSuccess,
            onError: () => {
                onError();
            },
        });
    }
};

const allowedDates = (date: unknown) => {
    let currentDate;
    if (
        typeof date === "string" ||
        typeof date === "number" ||
        date instanceof Date
    ) {
        currentDate = new Date(date);
    } else {
        throw new Error("Invalid date");
    }
    const specificDate = new Date(currentDate);
    specificDate.setDate(specificDate.getDate() - 1);

    return currentDate >= specificDate; // Allow only dates after or equal to the specific date
};

const formatTime = (timeString: string): string => {
    const [hours, minutes] = timeString.split(":");
    return `${hours.padStart(2, "0")}:${minutes.padStart(2, "0")}`;
};

const showDate = (d: Date) => {
    console.log(typeof form.visit_date);
    console.log(typeof d);

    const date = useDate();

    return date.format(d, "keyboardDate");
};
</script>

<template>
    <v-card max-width="90%" class="mx-auto mt-16" elevation="4">
        <v-card-title
            class="d-flex align-center justify-space-between text-grey-darken-4 text-h5 font-weight-bold"
        >
            <div class="d-flex align-center justify-center">
                <SvgIcon name="VisitIcon" />
                &nbsp; بيانات الزيارات
            </div>

            <div class="align-center">
                <v-chip-group
                    class="text-primary"
                    v-model="selection"
                    selected-class="bg-primary"
                    mandatory
                    column
                    v-bind="props"
                >
                    <v-chip
                        class="chip-hover"
                        variant="outlined"
                        @click="getSelectedPeriod(Period.YESTERDAY)"
                        >الأمس</v-chip
                    >
                    <v-chip
                        class="chip-hover"
                        variant="outlined"
                        @click="getSelectedPeriod(Period.TODAY)"
                        >اليوم</v-chip
                    >
                    <v-chip
                        class="chip-hover"
                        variant="outlined"
                        @click="getSelectedPeriod(Period.TOMORROW)"
                        >الغد</v-chip
                    >
                    <v-chip
                        class="chip-hover"
                        variant="outlined"
                        @click="getSelectedPeriod(Period.THIS_WEEK)"
                        >هذا الأسبوع</v-chip
                    >
                    <v-chip
                        class="chip-hover"
                        variant="outlined"
                        @click="getSelectedPeriod(Period.THIS_MONTH)"
                        >هذا الشهر</v-chip
                    >

                    <v-chip
                        class="chip-hover"
                        variant="outlined"
                        @click="customDateDialog = true"
                        >مخصص</v-chip
                    >
                </v-chip-group>
            </div>

            <!-- اختيار التاريخ المخصص -->
            <v-dialog v-model="customDateDialog" persistent max-width="400px">
                <v-card>
                    <v-card-title>حدد التاريخ</v-card-title>
                    <v-card-text>
                        <v-date-picker
                            width="100%"
                            height="100%"
                            title="تاريخ الزيارة"
                            v-model="customDateRange"
                            multiple="range"
                            color="primary"
                            bg-color="indiog-lighten-5"
                            density="compact"
                            class="font-weight-bold"
                        />
                    </v-card-text>
                    <v-card-actions>
                        <VBtnCancel @click="customDateDialog = false"
                            >إلغاء</VBtnCancel
                        >
                        <VBtnSave @click="getSelectedPeriod(Period.CUSTOME)"
                            >موافق</VBtnSave
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card-title>

        <v-divider></v-divider>

        <v-data-table
            height="340"
            :headers="headers"
            :items="visits"
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
            @click:row="editVisit"
        >
            <template v-slot:headers>
                <th class="font-weight-black text-h6" v-for="head in headers">
                    {{ head.title }}
                </th>
            </template>

            <template v-slot:item.num="{ index }">
                {{ index + 1 }}
            </template>

            <template v-slot:item.dentist_id="{ item, value }">
                <p class="font-weight-bold text-grey-darken-4">
                    {{ value == null ? "لا يوجد" : item.dentist.name }}
                </p>
            </template>

            <template v-slot:item.patient_id="{ item, value }">
                <p class="font-weight-bold text-grey-darken-4">
                    {{ value == null ? "لا يوجد" : item.patient.name }}
                </p>
            </template>

            <template v-slot:item.status_id="{ item, value }">
                <v-chip :color="item.status.color">
                    <p>
                        {{ value == null ? "لا يوجد" : item.status.name }}
                    </p>
                </v-chip>
            </template>

            <template v-slot:item.visit_time="{ item, value }">
                <p>
                    {{ formatTime(value) }}
                </p>
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
                                تسجيل زيارة
                            </VBtnAdd>
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

                            <v-form @submit.prevent="saveVisit">
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12" md="10" sm="12">
                                            <v-autocomplete
                                                v-model="form.patient_id"
                                                label="اسم المريض"
                                                :items="patients"
                                                item-title="name"
                                                item-value="id"
                                                no-data-text="لاتوجد بيانات خاصة بالمرضى"
                                                clearable
                                            ></v-autocomplete>
                                        </v-col>

                                        <v-col cols="12" md="10" sm="12">
                                            <v-autocomplete
                                                v-model="form.dentist_id"
                                                label="اسم الطبيب"
                                                :items="dentists"
                                                item-title="name"
                                                item-value="id"
                                                no-data-text="لاتوجد بيانات خاصة بالأطباء"
                                                clearable
                                            ></v-autocomplete>
                                        </v-col>

                                        <v-col cols="12" md="10" sm="12">
                                            <v-autocomplete
                                                v-model="form.status_id"
                                                :items="statuses"
                                                item-title="name"
                                                item-value="id"
                                                no-data-text="لاتوجد حالات خاصة بالزيارات"
                                                label="حالة الزيارة"
                                                clearable
                                            ></v-autocomplete>
                                        </v-col>

                                        <v-col cols="12" md="6" sm="12">
                                            <v-date-input
                                                v-model="visitDate"
                                                label="تاريخ الزيارة"
                                                prepend-icon=""
                                                variant="outlined"
                                                persistent-placeholder
                                                color="primary"
                                                cancel-text="إلغاء"
                                                density="compact"
                                                ok-text="موافق"
                                            ></v-date-input>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-text-input
                                                v-model="form.visit_time"
                                                :active="timeDialog"
                                                :focused="timeDialog"
                                                label="توقيت الزيارة"
                                                readonly
                                            >
                                                <v-dialog
                                                    v-model="timeDialog"
                                                    activator="parent"
                                                    width="auto"
                                                >
                                                    <v-time-picker
                                                        title="إختار الوقت"
                                                        color="primary"
                                                        bg-color="indigo-lighten-5"
                                                        v-if="timeDialog"
                                                        v-model="
                                                            form.visit_time
                                                        "
                                                    >
                                                        <template v-slot:title>
                                                            <p
                                                                class="text-primary font-weight-bold text-h6"
                                                            >
                                                                إختار الوقت
                                                            </p>
                                                        </template>
                                                    </v-time-picker>
                                                </v-dialog>
                                            </v-text-input>
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
                                        >هل انت متاكد من انك تريد حذف الزيارة
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
                    @click.stop="editVisit(item as Visit)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    @click.stop="deleteVisit(item as Visit)"
                    color="red-darken-1"
                    hint="حذف"
                >
                    mdi-delete
                </v-icon>
            </template>

            <template v-slot:no-data>
                <div class="font-weight-bold text-grey-darken-1">
                    لايوجد زيارات
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
