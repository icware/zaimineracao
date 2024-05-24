<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useToast } from 'primevue/usetoast';
import { ref, onMounted, onBeforeMount, reactive } from 'vue';
import { CompanyController } from '../CompanyController';

const controller = new CompanyController();
const toast = useToast();

const dt = ref(null);
const filters = ref({});
const submitted = ref(false);
const selectedObjects = ref(null);
const deleteDialog = ref(false);
const deleteDialogs = ref(false);
const objectDialog = ref(false)

const objectData = reactive({});

const editObject = (editObject) => {
    objectData.value = { ...editObject };

    objectDialog.value = true;
};

const openNew = () => {
    objectData.value = {};
    submitted.value = false;
    objectDialog.value = true;
};

const hideDialog = () => {
    objectDialog.value = false;
    submitted.value = false;
};

const objectSave = () => {

};

const confirmDeleteObject = (editObject) => {
    objectData.value = editObject;
    deletecompanyDialog.value = true;
};

const confirmDeleteSelected = () => {
    selectedObjects.value = true;
};

const deleteSelected = () => {
    data.value = data.value.filter((val) => !selectedcompanys.value.includes(val));
    deletecompanysDialog.value = false;
    selectedcompanys.value = null;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'companys Deleted', life: 10000 });
};

const props = defineProps({
    data: {
        type: Array,
        required: true,
    }
})

onBeforeMount(() => {
    initFilters();
});

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const { data } = props

</script>
<template>
    <div class="col-12">

        <Toolbar class="mb-4">
            <template v-slot:start>
                <div class="flex flex-column md:flex-row md:justify-content-start md:align-items-center">

                    <div class="w-full sm:w-auto">
                        <Button label="Salvar" icon="pi pi-save" class="p-button-text" @click="deletecompany" />
                    </div>

                    <div class="w-full sm:w-auto">
                        <Button label="Refresh" icon="pi pi-refresh" class="p-button-text" @click="deletecompany" />
                    </div>
                    <div class="w-full sm:w-auto">
                        <Button label="Novo" icon="pi pi-user-plus" class="p-button-text" @click="deletecompany" />
                    </div>
                </div>
            </template>
            <template v-slot:end>
                <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                    <IconField iconPosition="left" class="block mt-2 md:mt-0">
                        <InputIcon class="pi pi-search" />
                        <InputText class="w-full sm:w-auto" v-model="filters['global'].value"
                            placeholder="Pesquisar..." />
                    </IconField>
                </div>
            </template>
        </Toolbar>
        <DataTable ref="dt" :value="data" v-model:selection="selectedObjects" dataKey="id" :paginator="true" :rows="10"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25]"
            currentPageReportTemplate="Mostrando de {first} a {last} de {totalRecords} Itens">

            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column field="id" header="ID" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                <template #body="slotProps">
                    <span class="p-column-title">ID</span>
                    {{ slotProps.data.id }}
                </template>
            </Column>
            <Column field="name" header="Nome" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                <template #body="slotProps">
                    <span class="p-column-title">Code</span>
                    {{ slotProps.data.name }}
                </template>
            </Column>
            <Column field="email" header="Email" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                <template #body="slotProps">
                    <span class="p-column-title">Email</span>
                    {{ slotProps.data.email }}
                </template>
            </Column>
            <Column field="role" header="Acesso" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                <template #body="slotProps">
                    <span class="p-column-title">Acesso</span>
                    {{ slotProps.data.role }}
                </template>
            </Column>
            <Column headerStyle="min-width:10rem;">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded
                        @click="editObject(slotProps.data)" />
                    <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                        @click="confirmDelete(slotProps.data)" />
                </template>
            </Column>

        </DataTable>
        <Dialog v-model:visible="objectDialog" :style="{ width: '450px' }" header="Detalhe da empresa" :modal="true"
            class="p-fluid">

            <div class="p-fluid">
                <h5>Vertical</h5>
                <div class="field">
                    <label for="name1">Name</label>
                    <InputText id="name1" type="text" />
                </div>
                <div class="field">
                    <label for="email1">Email</label>
                    <InputText id="email1" type="text" disabled />
                </div>
                <div class="field">
                    <label for="age1">Age</label>
                    <InputText id="age1" type="text" />
                </div>
            </div>

            <div class="p-fluid">
                <h5>Vertical Grid</h5>
                <div class="formgrid grid">
                    <div class="field col">
                        <label for="name2">Name</label>
                        <InputText id="name2" type="text" />
                    </div>
                    <div class="field col">
                        <label for="email2">Email</label>
                        <InputText id="email2" type="text" />
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex flex-column md:flex-row md:justify-content-end md:align-items-center">
                    <div class="w-full sm:w-auto">
                        <Button icon="pi pi-save" severity="primary" label="Salvar" @click="hideDialog" />
                    </div>
                </div>
            </template>



        </Dialog>
    </div>


</template>