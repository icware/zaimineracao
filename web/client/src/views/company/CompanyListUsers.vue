<template>
    <div class="card ">
        <h1>Lista de usuários</h1>

        <div class="col-12 md:col-6">
            <div class="p-inputgroup" data-pc-name="inputgroup" data-pc-section="root"><button
                    class="p-button p-component" type="button" aria-label="Search" data-pc-name="button"
                    data-pc-section="root" data-pd-ripple="true">
                    <span class="p-button-label" data-pc-section="label">Convidar</span>
                </button><input class="p-inputtext p-component" placeholder="Email para convite"
                    data-pc-name="inputtext" data-pc-section="root"></div>
        </div>

    </div>
    <div class="card ">
        <DataTable :value="addArea" tableStyle="min-width: 50rem">
            <Column field="username" header="Usuário"></Column>
            <Column field="status" header="Status"></Column>
            <Column field="permission" header="Permissão"></Column>
            <Column field="options" header="Opções">
                <template #body="{ data }">
                    <Button label="Desabilitar" @click="show()" />
                </template>
            </Column>
        </DataTable>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
// Falta linkar com o servidor para trazer os dados reais
const addArea = ref([
    { username: 'Teste 1', status: 'Habilitado', permission: 'Administrador', button: [] },
    { username: 'Teste 2', status: 'Habilitado', permission: 'Administrador', button: [] },
]
);

const show = () => {
    toast.add({ severity: 'info', summary: 'Info', detail: 'Mensagem de Teste', life: 3000 });
};

</script>