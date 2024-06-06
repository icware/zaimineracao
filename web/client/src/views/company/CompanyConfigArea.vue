<template>
    <Card>
        <template #title>Configurações</template>
        <template #subtitle>{{ data.name }}</template>
        <template #content>
            <div class="card">
                <div class="grid flex justify-content-evenly gap-5">
                    <div class="flex flex-column gap-2">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model="data.name" aria-describedby="name-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="accumulated">Capacidade t/h</label>
                        <InputText id="accumulated" v-model="data.accumulated" aria-describedby="accumulated-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="minimum">Mínima t/h</label>
                        <InputText id="minimum" v-model="data.min" aria-describedby="minimum-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="average">Média t/h</label>
                        <InputText id="average" v-model="data.med" aria-describedby="average-help" />
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
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const router = useRouter();
const data = ref({
    name: 'Teste',
    accumulated: '200 t',
    min: '20%',
    med: '58%',
    visible: true,
    active: true,
    main: true,
    primary: true,
    order: 0,
});

const cancel = () => {
    router.push('/company/areas');
}


const show = () => {
    toast.add({ severity: 'info', summary: 'Info', detail: 'Mensagem de Teste', life: 3000 });
};


</script>

<style>
/* Adicione qualquer estilo personalizado aqui */
</style>
