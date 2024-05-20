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
const lineOptions = ref(null);

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



const applyLightTheme = () => {
    lineOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#495057'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            },
            y: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            }
        }
    };
};

const applyDarkTheme = () => {
    lineOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#ebedef'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            },
            y: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            }
        }
    };
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

const layout = ref('grid');
const sortKey = ref(null);
const sortOrder = ref(null);
const sortField = ref(null);
const sortOptions = ref([
    { label: 'Price High to Low', value: '!price' },
    { label: 'Price Low to High', value: 'price' }
]);

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
    setInterval(fetchData, 30000);
});


</script>

<template>

    <div class="gri">
        <div class="col-12">
            <h5> Displayes de produção </h5>
            <DataView :value="companyAreaDisplay" :layout="layout" :paginator="true" :rows="9" :sortOrder="sortOrder"
                :sortField="sortField">
                <template>
                    <div class="grid grid-nogutter">
                        <div class="col-6 text-left">
                            <Dropdown v-model="sortKey" :options="sortOptions" optionLabel="label"
                                placeholder="Sort By Price" @change="onSortChange($event)" />
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
                                    <img class="block xl:block mx-auto border-round w-full"
                                        :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`"
                                        :alt="item.name" />
                                    <Tag :value="item.inventoryStatus" :severity="getSeverity(item)" class="absolute"
                                        style="left: 4px; top: 4px"></Tag>
                                </div>
                                <div
                                    class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                    <div
                                        class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                        <div>
                                            <span class="font-medium text-secondary text-sm">{{ item.category }}</span>
                                            <div class="text-lg font-medium text-900 mt-2">{{ item.name }}</div>
                                        </div>
                                        <div class="surface-100 p-1" style="border-radius: 30px">
                                            <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2"
                                                style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                                <span class="text-900 font-medium text-sm">{{ item.rating }}</span>
                                                <i class="pi pi-star-fill text-yellow-500"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-column md:align-items-end gap-5">
                                        <span class="text-xl font-semibold text-900">${{ item.price }}</span>
                                        <div class="flex flex-row-reverse md:flex-row gap-2">
                                            <Button icon="pi pi-heart" outlined></Button>
                                            <Button icon="pi pi-shopping-cart" label="Buy Now"
                                                :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                                                class="flex-auto md:flex-initial white-space-nowrap"></Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template #grid="slotProps">
                    <div class="grid grid-nogutter">
                        <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 sm:col-6 md:col-4 p-2">
                            <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">

                                <div class="surface-50 flex justify-content-center border-round p-3">
                                    <div class="relative mx-auto">
                                        {{ item.name }}
                                    </div>
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
                                            <div v-if="item.flowrate.status" class="text-lg font-medium text-900 mt-1">
                                                Ativo /( {{ item.flowrate.value }} )
                                            </div>
                                            <div v-else="!item.flowrate.status">
                                                Inativo
                                            </div>
                                        </div>
                                    </div>


                                    <div class="flex flex-column gap-4 mt-4">
                                        <span class="text-2xl font-semibold text-900">{{ item.accumulated.value
                                            }}</span>
                                        <div class="flex gap-2">
                                            <Button icon="pi pi-shopping-cart" label="Abrir"
                                                :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                                                class="flex-auto white-space-nowrap"></Button>
                                        </div>
                                        <div class="chart-container">
                                            <Chart type="line" :data="item" :options="lineOptions" />
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


    <div class="grid p-fluid">
        <DashDisplay v-for="(area, index) in companyAreaDisplay" :key="index" :status="area.status" :name="area.name"
            :value="area.accumulated.value" :pde="area.accumulated.pde" :life="area.accumulated.life"
            :visible="area.visible" />
    </div>

</template>
