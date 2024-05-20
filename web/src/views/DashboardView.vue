<script setup>
import { onMounted, ref, watch } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';
import { CompanyService } from '@/service/company/CompanyService';
import { StoreService } from '@/service/StoreService';

// Componentes
import DashDisplay from '@/components/DashDisplay.vue';

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
        <DashDisplay v-for="(area, index) in companyAreaDisplay" :key="index" :status="area.status" :name="area.name"
            :value="area.accumulated.value" :pde="area.accumulated.pde" :life="area.accumulated.life"
            :visible="area.visible" />
    </div>

</template>
