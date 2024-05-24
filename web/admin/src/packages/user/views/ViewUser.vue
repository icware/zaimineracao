<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount, reactive } from 'vue';
import { UserController } from '@/packages/user/UserController';
import { useToast } from 'primevue/usetoast';

const controller = new UserController();

const toast = useToast();

const data = ref([]);
const user = reactive({
    first_name: ref(''),
    last_name: ref(''),
    email: ref(''),
    phone: ref(''),
    active: ref(false),
    company: ref(false),
    super: ref(false),
    birth: ref(null),
}

);

const userDialog = ref(false);
const deleteuserDialog = ref(false);
const deleteusersDialog = ref(false);
const selectedusers = ref(null);
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
    user.value = {};
    submitted.value = false;
    userDialog.value = true;
};

const hideDialog = () => {
    userDialog.value = false;
    submitted.value = false;
};

const saveuser = () => {
    submitted.value = true;

    if (user.value.id) {
        console.log('Enviar put para o servidor');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'user Updated', life: 10000 });
    } else {
        console.log(user.value.type)
        setData(user.value);
    }
    userDialog.value = false;
    user.value = {};

};

const edituser = (edituser) => {
    user.value = { ...edituser };
    userDialog.value = true;
};

const confirmDeleteuser = (edituser) => {
    user.value = edituser;
    deleteuserDialog.value = true;
};

const deleteuser = () => {
    data.value = data.value.filter((val) => val.id !== user.value.id);
    deleteuserDialog.value = false;
    user.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'user Deleted', life: 10000 });
};

const findIndexById = (id) => {
    return data.value.findIndex(user => user.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteusersDialog.value = true;
};

const deleteSelectedusers = () => {
    data.value = data.value.filter((val) => !selectedusers.value.includes(val));
    deleteusersDialog.value = false;
    selectedusers.value = null;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'users Deleted', life: 10000 });
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
                                :disabled="!selectedusers || !selectedusers.length" />
                        </div>
                    </template>
                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import"
                            chooseLabel="Import" class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable ref="dt" :value="data" v-model:selection="selectedusers" dataKey="id" :paginator="true"
                    :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} users">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Usuários</h5>
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
                                @click="edituser(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                @click="confirmDeleteuser(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="userDialog" :style="{ width: '450px' }" header="user Details" :modal="true"
                    class="p-fluid">

                    <div class="field">
                        <label for="name">Descrição</label>
                        <InputText id="name" v-model.trim="user.value.name" required="true" autofocus
                            :class="{ 'p-invalid': submitted && !user.value.name }" />
                        <small v-if="submitted && !user.value.name" class="p-error">Insira a descrição do
                            servidor</small>
                    </div>

                    <div class="field">
                        <label for="inventoryStatus" class="mb-3">Tipo de servidor</label>
                        <Dropdown id="inventoryStatus" v-model="user.value.type" :options="types" optionLabel="label"
                            placeholder="Selecione o tipo">
                            <template #value="slotProps">
                                <div v-if="slotProps.value && slotProps.value.value">
                                    <span :class="'user-badge status-' + slotProps.value.value">{{
                                        slotProps.value.label }}</span>
                                </div>
                                <div v-else-if="slotProps.value && !slotProps.value.value">
                                    <span :class="'user-badge status-' + slotProps.value.toLowerCase()">{{
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
                        <InputText id="address" v-model.trim="user.value.address" required="true" autofocus
                            :class="{ 'p-invalid': submitted && !user.value.address }" />
                        <small v-if="submitted && !user.value.address" class="p-error">Insira Endereço do
                            servidor</small>
                    </div>

                    <div class="field flex align-items-center gap-3">
                        <Checkbox v-model="user.value.enabled" :binary="true" />
                        <label for="enabled">Servidor ativo</label>
                    </div>

                    <div class="field">
                        <Button label="Save" icon="pi pi-check" @click="saveuser" />
                    </div>
                </Dialog>

                <Dialog v-model:visible="deleteuserDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete <b>{{ user.value.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteuser" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteusersDialog" header="Confirm" modal footerClass="p-dialog-footer"
                    :closable="false">
                    <div class="confirmation-content">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem;"></i>
                        <span>Are you sure you want to delete the selected users?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deleteusersDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedusers" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
