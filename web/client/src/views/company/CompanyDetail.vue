<template>
    <Card>
        <template #title>
            <div class="card-title">
                <h4>Produção</h4>
                <Button label="Exportar" />
            </div>
        </template>

        <template #content>
            <div class="card">
                <DataTable :value="addDetails">
                    <Column field="status" header="St">
                        <template #body="slotProps">
                            <i
                                :class="['pi', 'pi-circle-fill', slotProps.data.status === 'Ativo' ? 'status-green' : 'status-red']"></i>
                        </template>
                    </Column>
                    <Column field="name" header="Àrea"></Column>
                    <Column field="pde" header="Pde"></Column>
                    <Column field="accumulated" header="Acumulado"></Column>
                    <Column field="interval_filter" header="T.Filtro"></Column>
                    <Column field="life" header="T.Produção"></Column>
                    <Column field="life_percent" header="T.Improd"></Column>
                    <Column field="min_capacity" header="% Baixa"></Column>
                    <Column field="min" header="T.Baixa"></Column>
                    <Column field="min_percent" header="% P.Baixa"></Column>
                    <Column field="med_capacity" header="% Média"></Column>
                    <Column field="med" header="T.Média"></Column>
                    <Column field="med_percent" header="P.Média"></Column>
                    <Column field="max_capacity" header="% Alta"></Column>
                    <Column field="max" header="T.Alta"></Column>
                    <Column field="max_percent" header="P.Alta"></Column>
                </DataTable>
            </div>

            <div class="card">
                <div class="card-title">
                    <h4>Logística</h4>
                </div>

                <DataTable :value="addLogistics" tableStyle="min-width: 50rem">
                    <Column field="total" header="Total de Viagens"></Column>
                    <Column field="" header="% PDE de Ton"></Column>
                    <Column field="" header="Média de Ton"></Column>
                    <Column field="" header="Média de Tempo"></Column>
                </DataTable>

            </div>

            <div class="card">
                <div class="card-title">
                    <h4>Informações de Frota</h4>
                </div>

                <DataTable :value="addFrota" tableStyle="min-width: 50rem">
                    <Column field="description" header="Descrição"></Column>
                    <Column field="" header="Capacidade"></Column>
                    <Column field="" header="Total de Viagens"></Column>
                    <Column field="" header="Previsão"></Column>
                </DataTable>

            </div>

        </template>
    </Card>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useCompanyStore } from '../../store/CompanyStore';


const addDetails = ref([]);
const { getCompanyData } = useCompanyStore();
const addLogistics = ref([
    { total: 'Não existem dados para este périodo.' }
]);

const addFrota = ref([
    { description: 'Não existem dados para este périodo.' }
]);


const fetchCompanyData = async () => {
    const companyData = await getCompanyData;

    if (companyData && companyData.areas) {
        addDetails.value = companyData.areas.map(area => ({
            status: area.status ? 'Ativo' : 'Inativo',
            name: area.name,
            pde: area.accumulated.pde,
            accumulated: area.accumulated.value,
            interval_filter: area.interval_filter,
            life_percent: area.accumulated.life_percent,
            life: area.accumulated.life,
            min_capacity: area.timers.min_capacity,
            min: area.timers.min,
            min_percent: area.timers.min_percent,
            med_capacity: area.timers.med_capacity,
            med: area.timers.med,
            med_percent: area.timers.med_percent,
            max_capacity: area.timers.max_capacity,
            max: area.timers.max,
            max_percent: area.timers.max_percent

        }));
    }

}

onMounted(async () => {
    await fetchCompanyData();
});



</script>

<style scoped>
.status-green {
    color: green;
}

.status-red {
    color: red;
}

.card-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 1rem;
}

.card-title span {
    flex: 1;
    text-align: left;
}
</style>