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
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCompanyStore } from '../../store/CompanyStore';

const addArea = ref([]);
const router = useRouter();
const companyStore = useCompanyStore();

const configureArea = (data) => {
    router.push({
        path: '/company/areas/config',
        query: {
            name: data.name,
            capacity: data.capacity,
            minimum: data.minimum,
            average: data.average,
            visible: data.visible
        }
    });
};

onMounted(() => {
    const companyData = companyStore.getCompanyData;

    if (companyData && companyData.areas) {
        addArea.value = companyData.areas.map(area => ({
            name: area.name,
            capacity: area.accumulated.value,
            minimum: area.timers.min_capacity,
            average: area.timers.med_capacity,
            visible: area.visible ? 'Sim' : 'Não'
        }));
    }
});
</script>