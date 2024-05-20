<script setup>
import { onMounted, computed, ref, watch } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';
import { CompanyService } from '@/service/company/CompanyService';
import { StoreService } from '@/service/StoreService';

// Componentes
import DisplayHeader from '@/components/DisplayHeader.vue';
import ChartInline from '@/components/ChartInline.vue';
import TableInstant from '@/components/TableInstant.vue';
import TableTime from '@/components/TableTime.vue';

const { isDarkTheme } = useLayout();
const auth = useAuthStore();
const useStoreService = new StoreService();
const router = useRouter();

const companyData = ref({});
const companyAreaDisplay = ref({});
const companyAreasFlowrate = ref([]);
const companyAreasTimers = ref([]);
const companyAreasCharts = ref([]);


async function getCompanyData() {
    if (auth.getIsAuth) {
        const companySelected = useStoreService.getValue('company');
        if (companySelected) {
            const companyService = new CompanyService();
            const response = await companyService.getCompanyData(companySelected);
            companyData.value = response.data;
            companyAreaDisplay.value = response.data.areas;
            companyAreasFlowrate.value = response.data.areas.map(area => ({
                name: area.name,
                value: area.flowrate.value,
                percent: area.flowrate.percent,
                status: area.flowrate.status ? "Ativo" : "Inativo",
                first_time: area.flowrate.first_time,
                last_time: area.flowrate.last_time
            }));

            companyAreasTimers.value = response.data.areas.map(area => ({
                name: area.name,
                min: area.timers.min,
                med: area.timers.med,
                max: area.timers.max,
            }));

            companyAreasCharts.value = response.data.areas.map(area => ({

            }));

        }
    }
}

async function fetchData() {
    await getCompanyData();
}

onMounted(() => {
    fetchData();
    setInterval(fetchData, 30000); // Atualizar a cada 30 segundos
});

const applyLightTheme = () => {
    //    
};

const applyDarkTheme = () => {
    //  
};

watch(
    isDarkTheme,
    (val) => {
        if (val) {
            applyDarkTheme();
        } else {
            applyLightTheme();
        }
    },
    { immediate: true }
);

</script>

<template>
    <div class="grid p-fluid">
        <DisplayHeader v-for="(area, index) in companyAreaDisplay" :key="index" :status="area.status" :name="area.name"
            :value="area.accumulated.value" :pde="area.accumulated.pde" :life="area.accumulated.life"
            :visible="area.visible" />
    </div>

    <div class="col-12 xl:col-6">
        <div class="card">
            <h5>Linear Chart</h5>
            <Chart type="line" :data="lineData" :options="lineOptions"></Chart>
        </div>
    </div>


    <div class="card">
        <div class="flex justify-content-between align-items-center mb-0">
            <div class="col-12 xl:col-6">
                <h5>Produção Instantânea</h5>
                <DataTable :value="companyAreasFlowrate" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column field="name" header="Área" :sortable="true" style="width: 35%"></Column>
                    <Column field="first_time" header="Início" :sortable="true" style="width: 35%"></Column>
                    <Column field="last_time" header="Final" :sortable="true" style="width: 35%"></Column>
                    <Column field="value" header="Vazão" :sortable="true" style="width: 35%"></Column>
                    <Column field="percent" header="Pde" :sortable="true" style="width: 35%"></Column>
                    <Column field="status" header="Status" :sortable="true" style="width: 35%"></Column>
                </DataTable>

            </div>
            <div class="col-12 xl:col-6">
                <h5>Tempos</h5>
                <DataTable :value="companyAreasTimers" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column field="name" header="Área" :sortable="true" style="width: 35%"></Column>
                    <Column field="min" header="Baixa" :sortable="true" style="width: 35%"></Column>
                    <Column field="med" header="Média" :sortable="true" style="width: 35%"></Column>
                    <Column field="max" header="Alta" :sortable="true" style="width: 35%"></Column>
                </DataTable>
            </div>
        </div>
    </div>

</template>
