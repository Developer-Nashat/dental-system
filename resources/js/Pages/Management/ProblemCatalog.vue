<script setup lang="ts">
import { ref, watch } from "vue";
import { ProblemCatalog } from "../../types/ProblemCatalog";
import { Header } from "../../types/TableHeader";
import { useForm, usePage } from "@inertiajs/vue3";
import SvgIcon from "../../Components/SvgIcon.vue";
import { useToast, POSITION } from "vue-toastification";

const inertiaPage = usePage<{ flash: { message: string; error: string } }>();
const toast = useToast();
const problemCatalog = ref<ProblemCatalog>();
const dialog = ref<boolean>(false);
const dialogDelete = ref<boolean>(false);
const isEdit = ref<boolean>(false);
const page = ref<number>(1);
const problemCatalogsPerPage = ref<number>(5);
const search = ref<string>();
const headers = ref<Header[]>([
    { title: "#", align: "start", key: "num", sortable: false, maxWidth: "50" },
    {
        title: "اسم تصنيف المشكلة",
        align: "start",
        key: "name",
        minWidth: "180",
    },
    {
        title: "الإجرائات",
        align: "start",
        key: "actions",
        sortable: false,
        minWidth: "100",
    },
]);

const props = defineProps(["problemCatalogs"]);

const form = useForm({
    id: props.problemCatalogs.id,
    name: props.problemCatalogs.name,
    price: props.problemCatalogs.price,
    description: props.problemCatalogs.description,
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

const pageCount = () => {
    return props.problemCatalogs === null
        ? 0
        : Math.ceil(
              props.problemCatalogs.length /
                  Math.abs(problemCatalogsPerPage.value)
          );
};
const pagePaginationText = () => {
    return (
        // page.value.toString() +
        // "-" +
        // pageCount().toString() +
        // " من " +
        // props.problemCatalogs.length
        "عدد تصنيف المشاكل " + props.problemCatalogs.length
    );
};

const getFormTitle = () => {
    return isEdit.value ? "تعديل تصنيف مشكلة" : "إضافة تصنيف مشكلة جديدة";
};

watch(dialog, (val) => {
    val || closeDialog();
});

watch(dialogDelete, (val) => {
    val || closeDeleteDialog();
});

const editProblemCatalog = (item: ProblemCatalog) => {
    form.id = item.id;
    form.name = item.name;

    problemCatalog.value = item;

    isEdit.value = true;
    dialog.value = true;
};

const DeleteProblemCatalog = (item: ProblemCatalog) => {
    form.id = item.id;
    form.name = item.name;

    problemCatalog.value = item;

    dialogDelete.value = true;
};

const deleteItemConfirm = () => {
    form.delete(route("problemCatalog.delete", problemCatalog.value), {
        onSuccess: () => {
            form.reset();
            closeDeleteDialog();
        },
        onError: () => onError(),
    });
};

const closeDialog = () => {
    dialog.value = false;
};

const closeDeleteDialog = () => {
    dialogDelete.value = false;
};

const saveProblemCatalog = () => {
    const handleSuccess = () => {
        form.reset();
        closeDialog();
        onSuccess();
    };

    if (!isEdit.value) {
        form.post(route("problemCatalog.store"), {
            onSuccess: () => {
                handleSuccess();
            },

            onError: () => onError(),
        });
    } else {
        form.patch(route("problemCatalog.update", problemCatalog.value), {
            onSuccess: () => {
                isEdit.value = false;
                handleSuccess();
            },

            onError: () => onError(),
        });
    }
};
</script>

<template>
    <v-card max-width="90%" class="mx-auto mt-16" elevation="4">
        <v-card-title
            class="d-flex align-center pe-2 text-primary mb-2 text-h5 font-weight-bold"
        >
            <v-icon icon="mdi-video-input-component"></v-icon> &nbsp; تصنيف
            المشاكل

            <v-spacer></v-spacer>
        </v-card-title>

        <v-divider></v-divider>
        <v-data-table
            height="320"
            :headers="headers"
            :items="problemCatalogs"
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

                    <v-dialog v-model="dialog" max-width="40%" persistent>
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

                            <v-form
                                fast-fail
                                @submit.prevent="saveProblemCatalog"
                            >
                                <v-card-text>
                                    <v-row>
                                        <v-col>
                                            <v-text-input
                                                v-model="form.name"
                                                label="اسم تصنيف المشكلة"
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
                                        >هل انت متاكد من انك تريد حذف تصنيف
                                        المشكلة
                                    </span>
                                    <span
                                        class="font-weight-bold text-h6 text-indigo-lighten-1"
                                    >
                                        '{{ problemCatalog?.name }}'</span
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
                    @click="editProblemCatalog(item as ProblemCatalog)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    @click="DeleteProblemCatalog(item as ProblemCatalog)"
                    color="red-darken-1"
                    hint="حذف"
                >
                    mdi-delete
                </v-icon>
            </template>

            <template v-slot:no-data>
                <div class="font-weight-bold text-grey-darken-1">
                    لاتوجد بيانات لتصنيف مشاكل
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
