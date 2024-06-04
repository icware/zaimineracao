<template>
    <Card>
        <template #title>Nome da empresa</template>
        <template #content>
            <div class="card">
                <DataTable :value="addArea" tableStyle="min-width: 50rem">
                    <Column field="name" header="Área"></Column>
                    <Column field="capacity" header="Capacidade t/h"></Column>
                    <Column field="minimum" header="Mínima t/h"></Column>
                    <Column field="average" header="Média t/h"></Column>
                    <Column field="visible" header="Visibilidade"></Column>
                    <Column field="options" header="Opções">
                        <template #body="{ data }">
                            <Button label="Configurar" @click="configureArea(data)" />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </template>
    </Card>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const addArea = ref([
    {
        name: 'Primário',
        capacity: '500.0',
        minimum: '100.0',
        average: '150.0',
        visible: true,
    }
]);

const configureArea = () => {
    router.push('/company/areas/config',
    );
};



</script>