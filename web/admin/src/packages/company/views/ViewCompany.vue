<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useToast } from 'primevue/usetoast';
import { ref, onMounted, onBeforeMount, reactive } from 'vue';
import { CompanyController } from '../CompanyController';
import CompanyUsers from './CompanyUsers.vue';
import CompanyServices from './CompanyServices.vue';
import CompanyDisplays from './CompanyDisplays.vue';
import CompanyData from './CompanyData.vue';

const controller = new CompanyController();

const toast = useToast();

const data = ref([]);

const company = reactive({
    users: ref({}),
}


);

const companyDialog = ref(false);
const deletecompanyDialog = ref(false);
const deletecompanysDialog = ref(false);
const selectedcompanys = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);

const getBadgeSeverity = (inventoryStatus) => {
    switch (inventoryStatus.toLowerCase()) {
        case 'instock':
            return 'success';
        case 'lowstock':
            return 'warning';
        case 'outofstock':
            return 'danger';
        default:
            return 'info';
    }
};

async function setData(data) {
    try {
        await controller.set(data);
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Usuário cadastrado com sucesso', life: 10000 });
        getData();
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Erro', detail: error.message, life: 10000 });
    }
}

async function getData() {
    try {
        const response = await controller.get();
        data.value = response.data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Falha a buscar informações', detail: 'Buscar informações', life: 10000 });
    }
}

onBeforeMount(() => {
    initFilters();
});

onMounted(() => {
    getData();
});

const openNew = () => {
    company.value = {};
    submitted.value = false;
    companyDialog.value = true;
};

const hideDialog = () => {
    companyDialog.value = false;
    submitted.value = false;
};

const savecompany = () => {
    submitted.value = true;

    if (company.value.id) {
        console.log('Enviar put para o servidor');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'company Updated', life: 10000 });
    } else {
        console.log(company.value.type)
        setData(company.value);
    }
    companyDialog.value = false;
    company.value = {};

};

const editcompany = (editcompany) => {
    company.value = { ...editcompany };

    companyDialog.value = true;
};

const confirmDeletecompany = (editcompany) => {
    company.value = editcompany;
    deletecompanyDialog.value = true;
};

const deletecompany = () => {
    data.value = data.value.filter((val) => val.id !== company.value.id);
    deletecompanyDialog.value = false;
    company.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'company Deleted', life: 10000 });
};

const findIndexById = (id) => {
    return data.value.findIndex(company => company.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deletecompanysDialog.value = true;
};

const deleteSelectedcompanys = () => {
    data.value = data.value.filter((val) => !selectedcompanys.value.includes(val));
    deletecompanysDialog.value = false;
    selectedcompanys.value = null;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'companys Deleted', life: 10000 });
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="Novo" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                            <Button label="Deletar" icon="pi pi-trash" severity="danger" @click="confirmDeleteSelected"
                                :disabled="!selectedcompanys || !selectedcompanys.length" />
                        </div>
                    </template>
                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import"
                            chooseLabel="Import" class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable ref="dt" :value="data" v-model:selection="selectedcompanys" dataKey="id" :paginator="true"
                    :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} companys">

                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Clientes / Empresas</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value"
                                    placeholder="Pesquisar..." />
                            </IconField>
                        </div>
                    </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="code" header="Code" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Code</span>
                            {{ slotProps.data.code }}
                        </template>
                    </Column>
                    <Column field="name" header="Name" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Name</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="address" header="Endereço" :sortable="true"
                        headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">CNPJ</span>
                            {{ slotProps.data.cnpj }}
                        </template>
                    </Column>
                    <Column field="enabled" header="Status" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Status</span>
                            {{ slotProps.data.enabled }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded
                                @click="editcompany(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                @click="confirmDeletecompany(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="companyDialog" :style="{ width: '70vw' }" header="Detalhe da empresa"
                    :modal="true" class="p-fluid">

                    <!-- Inicio do bloco de company -->


                    <div class="card">

                        <TabView v-model:activeIndex="active">
                            <!--  -->
                            <TabPanel header="Dados da empresa">
                                <CompanyData :data="company.value" />
                            </TabPanel>
                            <!--  -->
                            <TabPanel header="Usuário">
                                <CompanyUsers :data="company.value.users" />
                            </TabPanel>

                            <!--  -->
                            <TabPanel header="Serviços">
                                <CompanyServices :data="company.services" />
                            </TabPanel>
                            <!--  -->
                            <TabPanel header="Displays">
                                <CompanyDisplays :data="company.displays" />
                            </TabPanel>
                        </TabView>


                        <div class="flex flex-column md:flex-row md:justify-content-end md:align-items-center">
                            <div class="w-full sm:w-auto">
                                <Button icon="pi pi-times" severity="danger" label="Fechar Janelas"
                                    @click="hideDialog" />
                            </div>
                        </div>
                    </div>


                    <!-- Final do bloco de company -->

                </Dialog>

                <Dialog v-model:visible="deletecompanyDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete <b>{{ company.value.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deletecompany" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deletecompanysDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete the selected companys?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deletecompanysDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedcompanys" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
