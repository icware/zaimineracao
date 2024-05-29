<template>
    <Card>
        <template #title>Configurações</template>
        <template #subtitle>Primário</template>
        <template #content>
            <div class="card">
                <div class="grid flex justify-content-evenly gap-5">
                    <div class="flex flex-column gap-2">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model="data.name" aria-describedby="name-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="capacity">Capacidade t/h</label>
                        <InputText id="capacity" v-model="data.capacity" aria-describedby="capacity-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="minimum">Mínima t/h</label>
                        <InputText id="minimum" v-model="data.minimum" aria-describedby="minimum-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="average">Média t/h</label>
                        <InputText id="average" v-model="data.average" aria-describedby="average-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="order">Ordem de exibição</label>
                        <InputNumber v-model="data.order" inputId="minmax-buttons" mode="decimal" showButtons :min="0"
                            :max="100" />
                    </div>
                </div>
            </div>

            <div class="grid flex flex-row justify-content-center gap-4 mt-5">
                <div class="flex gap-2">
                    <Checkbox v-model="data.active" binary variant="filled" />
                    <label for="active">Ativo</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.visible" binary variant="filled" />
                    <label class="md:fs-6" for="visible">Visível</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.main" binary variant="filled" />
                    <label for="main">Principal</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.primary" binary variant="filled" />
                    <label for="primary">Primário</label>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 justify-content-center mt-5">
                <Button label="Salvar" @click="show()" />
                <Button label="Cancelar" class="p-button-secondary" @click="cancel()" />
            </div>
        </template>
    </Card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const route = useRoute();
const router = useRouter();

const cancel = () => {
    router.push('/company/areas');
}

const data = ref({
    name: '',
    capacity: '',
    minimum: '',
    average: '',
    order: 0,
    visible: false,
    active: false,
    main: false,
    primary: false,
});

onMounted(() => {
    // Pegar os parâmetros da URL
    data.value.name = route.query.name || '';
    data.value.capacity = route.query.capacity || '';
    data.value.minimum = route.query.minimum || '';
    data.value.average = route.query.average || '';
    data.value.visible = route.query.visible === 'Sim';
    // Ajuste os valores conforme necessário
});

const show = () => {
    toast.add({ severity: 'info', summary: 'Info', detail: 'Mensagem de Teste', life: 3000 });
};
</script>

<style>
/* Adicione qualquer estilo personalizado aqui */
</style>
