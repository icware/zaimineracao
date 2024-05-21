<script setup>
import { computed, onMounted, ref, watch, nextTick } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';
import { CompanyService } from '@/service/company/CompanyService';
import { StoreService } from '@/service/StoreService';
import { useCompanyStore } from '@/store/CompanyStore';
import { optionsThemeLight, otptionsThemeDark } from '@/views/company/composables/company';

const { isDarkTheme } = useLayout();

const auth = useAuthStore();
const useCompany = useCompanyStore();

const useStoreService = new StoreService();
const router = useRouter();
const lineOptions = ref({});
const barOptions = ref({});

const companyAreaDisplay = ref({});

// Seção de filtro
const userFilter = ref(false);
const displayfilter = ref(false);
const urlFilter = ref('');
const startDate = ref(null);
const startTime = ref(null);
const endDate = ref(null);
const endTime = ref(null);

// Sessão do layout
const layout = ref('grid');
const sortKey = ref(null);
const sortOrder = ref(null);
const sortField = ref(null);

const sortOptions = ref([
    { label: 'Ativo', value: '!item.flowrate.status' },
    { label: 'Inativo', value: 'item.flowrate.status' }
]);

const chartOptions = ref([
    { label: 'Linhas', value: 'line' },
    { label: 'Barras', value: 'bar' }
]);

async function getCompanyData() {
    if (auth.getIsAuth) {
        const companySelected = useStoreService.getValue('company');
        if (companySelected) {
            const companyService = new CompanyService();
            const response = await companyService.getCompanyData(companySelected, urlFilter.value || '');
            useCompany.setData(response.data);
        }
    }
}

function getLocalData() {
    companyAreaDisplay.value = useCompany.getCompanyData;
}

async function fetchData() {
    await getCompanyData();
    getLocalData();
}


const applyLightTheme = () => {
    lineOptions.value = optionsThemeLight.lineOptions
    barOptions.value = optionsThemeLight.barOptions
}

const applyDarkTheme = () => {
    lineOptions.value = otptionsThemeDark.lineOptions
    barOptions.value = otptionsThemeDark.barOptions
}

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


const onSortChange = (event) => {
    const value = event.value.value;
    const sortValue = event.value;

    if (value.indexOf('!') === 0) {
        sortOrder.value = -1;
        sortField.value = value.substring(1, value.length);
        sortKey.value = sortValue;
    } else {
        sortOrder.value = 1;
        sortField.value = value;
        sortKey.value = sortValue;
    }
};

const onChartShowChange = (value, itemId) => {
    const showChartSettings = useStoreService.getJson('showChartSettings') || {};
    showChartSettings[itemId] = value;
    useStoreService.setJson('showChartSettings', showChartSettings);
}
const onChartTypeChange = (value, itemId) => {
    const CahrtTypeSettings = useStoreService.getJson('CahrtTypeSettings') || {};
    CahrtTypeSettings[itemId] = value;
    useStoreService.setJson('CahrtTypeSettings', CahrtTypeSettings);
    console.log(CahrtTypeSettings);
}

const getSeverity = (product) => {
    switch (product.inventoryStatus) {
        case 'INSTOCK':
            return 'success';

        case 'LOWSTOCK':
            return 'warning';

        case 'OUTOFSTOCK':
            return 'danger';

        default:
            return null;
    }
};

onMounted(() => {
    fetchData();
    getLocalData();
    setInterval(getLocalData, 5000);
    setInterval(fetchData, 30000);

});


const OpenFilter = () => {
    displayfilter.value = true;
};

const applyFilter = async () => {
    displayfilter.value = false;
    urlFilter.value = buildUrlParams();
    await fetchData();
    await nextTick();
};

const formatDate = (date) => {
    if (!date) return '';
    const isoDate = new Date(date);
    const year = isoDate.getFullYear();
    const month = String(isoDate.getMonth() + 1).padStart(2, '0');
    const day = String(isoDate.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const formatTime = (time) => {
    if (!time) return '';
    const [hours, minutes, seconds] = time.split(':');
    const formattedTime = `${hours}%3A${minutes}`;
    return seconds ? `${formattedTime}%3A${seconds}` : formattedTime;
};

const buildUrlParams = () => {
    const queryParams = {};
    if (startDate.value) queryParams["date_start"] = formatDate(startDate.value);
    if (endDate.value) queryParams["date_end"] = formatDate(endDate.value);
    if (startTime.value) queryParams["time_start"] = formatTime(startTime.value);
    if (endTime.value) queryParams["time_end"] = formatTime(endTime.value);
    return new URLSearchParams(queryParams).toString();
};

const removeFilter = async () => {
    userFilter.value = false;
    displayfilter.value = false;
    urlFilter.value = null;
    startDate.value = null;
    startTime.value = null;
    endDate.value = null;
    endTime.value = null;
    await fetchData();
    await nextTick();
}
</script>

<template>

    <div class="grid">
        <div class="col-12">
            <div class="cad">
                <h5> Displayes de produção </h5>
                <DataView :value="companyAreaDisplay.areas" :layout="layout" :paginator="true" :rows="9"
                    :sortOrder="sortOrder" :sortField="sortField">
                    <template #header>
                        <div class="grid grid-nogutter">
                            <div class="col-6 text-left">
                                <Dropdown v-model="sortKey" :options="sortOptions" optionLabel="label" class="mr-2"
                                    placeholder="Ordenar por Status" @change="onSortChange($event)" />

                                <Button label="Filtro" icon="pi pi-filter-fill" style="width: auto"
                                    @click="OpenFilter" />

                            </div>

                            <div class="col-6 text-right">
                                <DataViewLayoutOptions v-model="layout" />
                            </div>
                        </div>

                    </template>

                    <template #list="slotProps">
                        <div class="grid grid-nogutter">
                            <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                                <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3"
                                    :class="{ 'border-top-1 surface-border': index !== 0 }">
                                    <div class="md:w-10rem relative">
                                        {{ item.name }}
                                    </div>
                                    <div
                                        class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                        <div
                                            class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="font-medium text-secondary text-sm"> Inicio </span>
                                                <div class="text-lg font-medium text-900 mt-2">{{
                                                    item.flowrate.first_time
                                                }}</div>
                                            </div>
                                            <div>
                                                <span class="font-medium text-secondary text-sm"> Final </span>
                                                <div class="text-lg font-medium text-900 mt-2">{{
                                                    item.flowrate.last_time
                                                }}</div>
                                            </div>
                                        </div>
                                        <div
                                            class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="font-medium text-secondary text-sm"> Final </span>
                                                <div class="text-lg font-medium text-900 mt-2">{{
                                                    item.flowrate.last_time
                                                }}</div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="font-medium text-secondary text-sm"> Final </span>
                                                <div class="text-lg font-medium text-900 mt-2">{{
                                                    item.flowrate.last_time
                                                }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-column md:align-items-end gap-5">
                                            <span class="text-xl font-semibold text-900">${{ item.price }}</span>
                                            <div class="flex flex-row-reverse md:flex-row gap-2">
                                                <span class="text-2xl font-semibold text-900">{{ item.accumulated.value
                                                    }}
                                                </span>
                                                <span class="text-2xl font-semibold text-900">{{ item.accumulated.pde
                                                    }}</span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template #grid="slotProps">
                        <div class="grid grid-nogutter">
                            <div v-for="(item, index) in slotProps.items" :key="index"
                                class="col-12 sm:col-6 md:col-4 p-2">
                                <div v-if="item.visible"
                                    class="p-4 border-1 surface-border surface-card border-round flex flex-column">

                                    <div class="flex justify-content-center border-round p-3 gap-3">
                                        <div class="relative mx-auto mr-3">
                                            {{ item.name }}
                                        </div>
                                        <span class="text-2xl font-semibold text-900">{{ item.accumulated.value
                                            }}
                                        </span>
                                        <span class="text-2xl font-semibold text-900">{{ item.accumulated.pde
                                            }}</span>
                                        <div class="flex align-items-center gap-2 justify-content-center py-1 px-2">
                                            <i v-if="item.status" class="pi pi-sitemap text-green-500"></i>
                                            <i v-else="!item.status" class="pi pi-sitemap text-red-500"></i>
                                        </div>
                                    </div>

                                    <div class="pt-4">

                                        <div class="flex flex-row justify-content-between align-items-start gap-2">
                                            <div class="p-1" style="border-radius: 30px">
                                                Hora Inicial
                                            </div>
                                            <div>
                                                <div v-if="item.flowrate.first_time"
                                                    class="text-lg font-medium text-900 mt-1">
                                                    {{ item.flowrate.first_time }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-row justify-content-between align-items-start gap-2">
                                            <div class="p-1" style="border-radius: 30px">
                                                Situação
                                            </div>
                                            <div>
                                                <div v-if="item.flowrate.status"
                                                    class="text-lg font-medium text-900 mt-1">
                                                    Ativo / {{ item.flowrate.value }}
                                                </div>
                                                <div v-else="!item.flowrate.status">
                                                    Inativo
                                                </div>
                                            </div>
                                        </div>


                                        <div class="flex flex-column gap-4 mt-4">

                                            <div v-if="item.showChart" class="chart-container">

                                                <Chart v-if="item.chartType === 'bar'" type="bar" :data="item.chart"
                                                    :options="barOptions" />
                                                <Chart v-else="item.chartType === 'line'" type="line" :data="item.chart"
                                                    :options="lineOptions" />

                                                <Dropdown v-model="item.chartType" :options="chartOptions"
                                                    optionLabel="label" class="mr-2" placeholder="Tipo de grafivo"
                                                    @change="onChartTypeChange(item.chartType, item.id)" />
                                            </div>
                                            <div>
                                                <Checkbox v-model="item.showChart" id="item.id" binary class="mr-2"
                                                    @change="onChartShowChange(item.showChart, item.id)" />
                                                <label for="item.id" class="mr-3">Exibir grafico</label>
                                            </div>
                                            <div class="flex gap-2">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                </DataView>
            </div>
        </div>
    </div>

    <Dialog header="Filtro" v-model:visible="displayfilter" :breakpoints="{ '960px': '75vw' }"
        :style="{ width: '30vw' }" :modal="true">
        <p class="line-height-3 m-0">
        <h5>Data Inicial</h5>
        <div class="flex col-10 gap-3">
            <span>
                <Calendar v-model="startDate" showIcon iconDisplay="input" />
            </span>
            <Calendar v-model="startTime" showIcon iconDisplay="input" timeOnly>
                <template #inputicon="{ clickCallback }">
                    <InputIcon class="pi pi-clock cursor-pointer" @click="clickCallback" />
                </template>
            </Calendar>
        </div>

        <h5>Data Final</h5>
        <div class="flex col-10 gap-3">
            <span>
                <Calendar v-model="endDate" showIcon iconDisplay="input" />
            </span>
            <Calendar v-model="endTime" showIcon iconDisplay="input" timeOnly>
                <template #inputicon="{ clickCallback }">
                    <InputIcon class="pi pi-clock cursor-pointer" @click="clickCallback" />
                </template>
            </Calendar>
        </div>
        </p>
        <template #footer>
            <Button label="Remover Filtro" @click="removeFilter" icon="pi pi-check" class="p-button-outlined" />
            <Button label="Aplicar Filtro" @click="applyFilter" icon="pi pi-check" class="p-button-outlined" />
        </template>
    </Dialog>

</template>
