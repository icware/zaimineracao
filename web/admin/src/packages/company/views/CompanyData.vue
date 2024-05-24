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
    <div class="p-fluid">
        <div class="col-12">

            <h5>Indentificadores</h5>
            <div class="p-fluid formgrid grid">
                <div class="field col-12 md:col-6">
                    <InputGroup>
                        <InputGroupAddon>
                            <Button icon="pi pi-copy" aria-label="Submit"></Button>
                        </InputGroupAddon>
                        <InputText placeholder="CNPJ" />
                        <InputGroupAddon>
                            <Button icon="pi pi-search" aria-label="Submit"></Button>
                        </InputGroupAddon>
                    </InputGroup>
                </div>

                <div class="field col-12 md:col-6">
                    <InputGroup>
                        <InputGroupAddon>
                            <Button icon="pi pi-copy" aria-label="Submit"></Button>
                        </InputGroupAddon>
                        <InputText placeholder="Código" disabled />
                    </InputGroup>
                </div>
                <div class="field col-12 md:col-4">
                    <label for="status">Status</label>
                    <InputSwitch aria-label="Remember Me" />
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-6">
                        <label for="company_name">Razão Social</label>
                        <InputText id="company_name" type="text" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="trading_name">Nome Fantasia</label>
                        <InputText id="trading_name" type="text" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="responsible_cpf">CPF do Responsável</label>
                        <InputText id="responsible_cpf" type="text" />
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-3">
                        <label for="address_type">Tipo Logradouro</label>
                        <InputText id="address_type" type="text" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="address">Logradouro</label>
                        <InputText id="address" type="text" />
                    </div>
                    <div class="field col-12 md:col-3">
                        <label for="number">Número</label>
                        <InputText id="number" type="text" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="complement">Complemento</label>
                        <InputText id="complement" type="text" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="neighborhood">Bairro</label>
                        <InputText id="neighborhood" type="text" />
                    </div>
                </div>
                <!--  -->
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-3">
                        <label for="postal_code">CEP</label>
                        <InputText id="postal_code" type="text" />
                    </div>
                    <div class="field col-12 md:col-3">
                        <label for="state">Estado</label>
                        <InputText id="state" type="text" />
                    </div>
                    <div class="field col-12 md:col-3">
                        <label for="country">País</label>
                        <InputText id="country" type="text" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <h5>Grupo 4: E-mail, Status, Situação Cadastral</h5>
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-3">
                        <label for="phone">Telefone</label>
                        <InputText id="phone" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="email">E-mail</label>
                        <InputText id="email" type="text" />
                    </div>

                    <div class="field col-12 md:col-4">
                        <label for="registration_status">Situação Cadastral</label>
                        <InputText id="registration_status" type="text" />
                    </div>
                </div>
            </div>
        </div>


    </div>


</template>