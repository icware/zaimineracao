<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount, reactive } from 'vue';
import { ServiceController } from '@/packages/service/ServiceController';
import { ServerController } from '@/packages/server/ServerController';
import { useToast } from 'primevue/usetoast';

const controller = new ServiceController();
const controllerServer = new ServerController();

const toast = useToast();

const data = ref([]);

const object = reactive({
    id: ref(null),
    code: ref(''),
    name: ref(''),
    server: ref(null),
    type: ref(''),
    enabled: ref(false),
    settings: ref([]),
}
);

const servers = ref({});

const objectDialog = ref(false);
const deleteobjectDialog = ref(false);
const deleteobjectsDialog = ref(false);
const selectedobjects = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);


const types = ref([
    { label: 'Interno', value: 'internal' },
    { label: 'Externo', value: 'external' },

]);

const listServers = ref([]);


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
            object: data.object,
            type: data.type.value,
            enabled: data.enabled,
            server: data.server.value,
            settings: data.settings,
        }
        const response = await controller.set(requestData);
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Serviço cadastrado com sucesso', life: 5000 });
        getData();
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Erro', detail: error.message, life: 5000 });
    }
}

async function getData() {
    try {
        const response = await controller.get();
        data.value = response.data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Falha a buscar informações', detail: 'Buscar informações', life: 5000 });
    }
}

async function getDataServer() {
    try {
        const response = await controllerServer.get();
        servers.value = response.data.data;
        listServers.value = servers.value.map(server => ({
            label: `${server.name} (${server.code})`,
            value: server.id
        }));
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
    getDataServer();
    object.value = {};
    submitted.value = false;
    objectDialog.value = true;
};

const hideDialog = () => {
    objectDialog.value = false;
    submitted.value = false;
};

const saveobject = () => {
    submitted.value = true;

    if (object.value.id) {
        console.log('Enviar put para o servidor');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'object Updated', life: 5000 });
    } else {
        console.log(object.value.type)
        setData(object.value);
    }
    objectDialog.value = false;
    object.value = {};

};

const editobject = (editobject) => {
    object.value = { ...editobject };
    objectDialog.value = true;
};

const confirmDeleteobject = (editobject) => {
    object.value = editobject;
    deleteobjectDialog.value = true;
};

const deleteobject = () => {
    data.value = data.value.filter((val) => val.id !== object.value.id);
    deleteobjectDialog.value = false;
    object.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'object Deleted', life: 5000 });
};

const findIndexById = (id) => {
    return data.value.findIndex(object => object.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteobjectsDialog.value = true;
};

const deleteSelectedobjects = () => {
    data.value = data.value.filter((val) => !selectedobjects.value.includes(val));
    deleteobjectsDialog.value = false;
    selectedobjects.value = null;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'objects Deleted', life: 5000 });
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
                                :disabled="!selectedobjects || !selectedobjects.length" />
                        </div>
                    </template>
                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import"
                            chooseLabel="Import" class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable ref="dt" :value="data" v-model:selection="selectedobjects" dataKey="id" :paginator="true"
                    :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} objects">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Serviços</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value"
                                    placeholder="Pesquisar..." />
                            </IconField>
                        </div>
                    </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="code" header="Código" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Code</span>
                            {{ slotProps.data.code }}
                        </template>
                    </Column>
                    <Column field="name" header="Descrição" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Descrição</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="enabled" header="Status" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Status</span>
                            {{ slotProps.data.enabled }}
                        </template>
                    </Column>
                    <Column field="enabled" header="Tipo de serviço" :sortable="true"
                        headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Tipo</span>
                            {{ slotProps.data.type }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded
                                @click="editobject(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                @click="confirmDeleteobject(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="objectDialog" :style="{ width: '450px' }" header="object Details" :modal="true"
                    class="p-fluid">

                    <div class="field">
                        <label for="name">Descrição</label>
                        <InputText id="name" v-model.trim="object.value.name" required="true" autofocus
                            :class="{ 'p-invalid': submitted && !object.value.name }" />
                        <small v-if="submitted && !object.value.name" class="p-error">Insira a descrição do
                            servidor</small>
                    </div>

                    <div class="field">
                        <label for="inventoryStatus" class="mb-3">Tipo de serviço</label>
                        <Dropdown id="inventoryStatus" v-model="object.value.type" :options="types" optionLabel="label"
                            placeholder="Selecione o tipo">
                            <template #value="slotProps">
                                <div v-if="slotProps.value && slotProps.value.value">
                                    <span :class="'object-badge status-' + slotProps.value.value">{{
                                        slotProps.value.label
                                    }}</span>
                                </div>
                                <div v-else-if="slotProps.value && !slotProps.value.value">
                                    <span :class="'object-badge status-' + slotProps.value.toLowerCase()">{{
                                        slotProps.value }}</span>
                                </div>
                                <span v-else>
                                    {{ slotProps.placeholder }}
                                </span>
                            </template>
                        </Dropdown>
                    </div>


                    <div class="field">
                        <label for="inventoryStatus" class="mb-3">Servidor</label>
                        <Dropdown id="inventoryStatus" v-model="object.value.server" :options="listServers"
                            optionLabel="label" placeholder="Selecion servidor">
                            <template #value="slotProps">
                                <div v-if="slotProps.value && slotProps.value.value">
                                    <span :class="'object-badge status-' + slotProps.value.value">{{
                                        slotProps.value.label }}</span>
                                </div>
                                <div v-else-if="slotProps.value && !slotProps.value.value">
                                    <span :class="'object-badge status-' + slotProps.value.toLowerCase()">{{
                                        slotProps.value }}</span>
                                </div>
                                <span v-else>
                                    {{ slotProps.placeholder }}
                                </span>
                            </template>
                        </Dropdown>
                    </div>

                    <div class="field flex align-items-center gap-3">
                        <Checkbox v-model="object.value.enabled" :binary="true" />
                        <label for="enabled">Serviço ativo</label>
                    </div>

                    <div class="field">
                        <Button label="Save" icon="pi pi-check" @click="saveobject" />
                    </div>
                </Dialog>

                <Dialog v-model:visible="deleteobjectDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete <b>{{ object.value.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteobject" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteobjectsDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete the selected objects?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deleteobjectsDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedobjects" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
