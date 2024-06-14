import { defineStore } from 'pinia';
import { computed, reactive, ref } from 'vue';
import { StoreService } from '@/service/StoreService';

const useStoreService = new StoreService();

export const useCompanyStore = defineStore('company', () => {
    const companies = ref(useStoreService.getJson('companies'));
    const company = ref(useStoreService.getJson('companye'));
    const associates = ref(useStoreService.getJson('associate'));
    const companyData = ref(useStoreService.getJson('companyData'));
    const storedShowChartSettings = ref(useStoreService.getJson('showChartSettings'));
    const storedChartTypeSettings = ref(useStoreService.getJson('CahrtTypeSettings'));

    function setCompanies(data) {
        useStoreService.setJson('companies', data);
    }

    function clearData() {
        useStoreService.del(companyData);
    }

    function setData(data) {
        const dataProcess = reactive({
            company: data.company,
            areas: data.areas.map((area) => {
                const showChartSettings = useStoreService.getJson('showChartSettings') || {};
                const CahrtTypeSettings = useStoreService.getJson('CahrtTypeSettings') || {};

                const showChart = showChartSettings[area.id] || true;
                const chartType = CahrtTypeSettings[area.id] || 'line';
                return {
                    ...area,
                    accumulated: area.accumulated || {},
                    flowrate: area.flowrate || {},
                    timers: area.timers || {},
                    showChart,
                    chartType,
                    chart: {
                        datasets: [],
                        labels: [],
                    },
                };
            }),
            meter: data.meter,
            logistica: data.logistica,
        });

        if (data.areas && data.areas.length > 0) {
            data.areas.forEach((area, index) => {
                if (area.chart && area.chart.result && area.chart.result.length > 0) {
                    const labels = new Set();
                    const dataSetsMap = {};

                    area.chart.result.forEach((entry) => {
                        labels.add(entry.label);
                    });

                    const uniqueLabels = Array.from(labels).sort();

                    const dataset = {
                        label: area.name,
                        data: uniqueLabels.map((label) => {
                            const entry = area.chart.result.find((e) => e.label === label);
                            return entry ? entry.value : 0;
                        }),
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                    };

                    dataProcess.areas[index].chart.datasets.push(dataset);
                    dataProcess.areas[index].chart.labels = uniqueLabels;
                }
            });

            useStoreService.setJson('companyData', dataProcess);
            companyData.value = useStoreService.getJson('companyData');
        }
    }

    function setCompany(data) {
        useStoreService.setJson('company', data);
    }

    function setAssociates(data) {
        useStoreService.setJson('associate', data);
    }

    const getCompanies = computed(() => {
        return companies.value;
    });

    const getCompanyData = computed(() => {
        return companyData.value;
    });

    const getCompany = computed(() => {
        return company.value;
    });

    const getAssociates = computed(() => {
        return associates.value;
    });

    const getAreaData = (id) => {
        return companyData.value.areas.find((area) => area.id === id);
    };

    return {
        setAssociates,
        setCompanies,
        setCompany,
        setData,
        clearData,
        getAssociates,
        getCompanyData,
        getCompanies,
        getCompany,
        getAreaData,
    };
});
