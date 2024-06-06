<template>
    <Card>
        <template #title>{{ companyName }}</template>
        <template #content>
            <div class="card">
                <DataTable :value="addArea" tableStyle="min-width: 50rem">
                    <Column field="name" header="Área"></Column>
                    <Column field="accumulated" header="Capacidade t/h"></Column>
                    <Column field="min" header="Mínima t/h"></Column>
                    <Column field="med" header="Média t/h"></Column>
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
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useCompanyStore } from '../../store/CompanyStore';

const router = useRouter();
const useComany = useCompanyStore();
const addArea = ref([]);
const companyName = ref('Nome da Company');

const configureArea = () => {
    router.push('/company/areas/config',
    );
};



onMounted(() => {
    const companyData = useComany.getCompanyData;

    if (companyData && companyData.areas) {
        addArea.value = companyData.areas.map(area => ({
            name: area.name,
            accumulated: area.accumulated.value,
            min: area.timers.min_capacity,
            med: area.timers.med_capacity,
            visible: area.visible ? 'Sim' : 'Não'
        }));
    }
});


</script>