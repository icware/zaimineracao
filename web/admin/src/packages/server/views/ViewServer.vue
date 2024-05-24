<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount, reactive } from 'vue';
import { ServerController } from '@/packages/server/ServerController';
import { useToast } from 'primevue/usetoast';

const controller = new ServerController();

const toast = useToast();

const data = ref([]);
const server = reactive({
    id: ref(null),
    code: ref(''),
    name: ref(''),
    address: ref(''),
    type: ref(''),
    enabled: ref(false),
}

);

const serverDialog = ref(false);
const deleteserverDialog = ref(false);
const deleteserversDialog = ref(false);
const selectedservers = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);
const types = ref([
    { label: 'Interno', value: 'internal' },
    { label: 'Externo', value: 'external' },

]);

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
        const requestData = {
            name: data.name,
            address: data.address,
            type: data.type.value,
            enabled: data.enabled,
        }
        const response = await controller.set(requestData);
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Servidor cadastrado com sucesso', life: 3000 });
        getData();
        console.log(response.data);
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Erro', detail: error.message, life: 3000 });
    }
}

async function getData() {
    try {
        const response = await controller.get();
        data.value = response.data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Falha a buscar informações', detail: 'Buscar informações', life: 3000 });
    }
}

onBeforeMount(() => {
    initFilters();
});

onMounted(() => {
    getData();
});

const openNew = () => {
    server.value = {};
    submitted.value = false;
    serverDialog.value = true;
};

const hideDialog = () => {
    serverDialog.value = false;
    submitted.value = false;
};

const saveServer = () => {
    submitted.value = true;

    if (server.value.id) {
        console.log('Enviar put para o servidor');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Server Updated', life: 3000 });
    } else {
        console.log(server.value.type)
        setData(server.value);
    }
    serverDialog.value = false;
    server.value = {};

};

const editServer = (editServer) => {
    server.value = { ...editServer };
    serverDialog.value = true;
};

const confirmDeleteServer = (editServer) => {
    server.value = editServer;
    deleteserverDialog.value = true;
};

const deleteServer = () => {
    data.value = data.value.filter((val) => val.id !== server.value.id);
    deleteserverDialog.value = false;
    server.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Server Deleted', life: 3000 });
};

const findIndexById = (id) => {
    return data.value.findIndex(server => server.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteserversDialog.value = true;
};

const deleteSelectedServers = () => {
    data.value = data.value.filter((val) => !selectedservers.value.includes(val));
    deleteserversDialog.value = false;
    selectedservers.value = null;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Servers Deleted', life: 3000 });
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
                                :disabled="!selectedservers || !selectedservers.length" />
                        </div>
                    </template>
                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import"
                            chooseLabel="Import" class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable ref="dt" :value="data" v-model:selection="selectedservers" dataKey="id" :paginator="true"
                    :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} servers">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Servidores</h5>
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
                            <span class="p-column-title">Endereço</span>
                            {{ slotProps.data.address }}
                        </template>
                    </Column>
                    <Column field="enabled" header="Status" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Status</span>
                            {{ slotProps.data.enabled }}
                        </template>
                    </Column>
                    <Column field="enabled" header="Tipo de servidor" :sortable="true"
                        headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Tipo</span>
                            {{ slotProps.data.type }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded
                                @click="editServer(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                @click="confirmDeleteServer(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="serverDialog" :style="{ width: '450px' }" header="Server Details" :modal="true"
                    class="p-fluid">

                    <div class="field">
                        <label for="name">Descrição</label>
                        <InputText id="name" v-model.trim="server.value.name" required="true" autofocus
                            :class="{ 'p-invalid': submitted && !server.value.name }" />
                        <small v-if="submitted && !server.value.name" class="p-error">Insira a descrição do
                            servidor</small>
                    </div>

                    <div class="field">
                        <label for="inventoryStatus" class="mb-3">Tipo de servidor</label>
                        <Dropdown id="inventoryStatus" v-model="server.value.type" :options="types" optionLabel="label"
                            placeholder="Selecione o tipo">
                            <template #value="slotProps">
                                <div v-if="slotProps.value && slotProps.value.value">
                                    <span :class="'server-badge status-' + slotProps.value.value">{{
                                        slotProps.value.label }}</span>
                                </div>
                                <div v-else-if="slotProps.value && !slotProps.value.value">
                                    <span :class="'server-badge status-' + slotProps.value.toLowerCase()">{{
                                        slotProps.value }}</span>
                                </div>
                                <span v-else>
                                    {{ slotProps.placeholder }}
                                </span>
                            </template>
                        </Dropdown>
                    </div>

                    <div class="field">
                        <label for="address">Endereço</label>
                        <InputText id="address" v-model.trim="server.value.address" required="true" autofocus
                            :class="{ 'p-invalid': submitted && !server.value.address }" />
                        <small v-if="submitted && !server.value.address" class="p-error">Insira Endereço do
                            servidor</small>
                    </div>

                    <div class="field flex align-items-center gap-3">
                        <Checkbox v-model="server.value.enabled" :binary="true" />
                        <label for="enabled">Servidor ativo</label>
                    </div>

                    <div class="field">
                        <Button label="Save" icon="pi pi-check" @click="saveServer" />
                    </div>
                </Dialog>

                <Dialog v-model:visible="deleteserverDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete <b>{{ server.value.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteServer" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteserversDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete the selected servers?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deleteserversDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedServers" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
