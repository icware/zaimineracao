<template>
    <div class="col-12 lg:col-6 xl:col-6 mb-3">
        <div class="card mb-3">
            <h5>Desempenho</h5>
            <div class="chart-container">
                <Chart type="line" :data="lineData" :options="lineOptions" />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, reactive, ref, watch } from 'vue';
    import { useLayout } from '@/layout/composables/layout';

    const { isDarkTheme } = useLayout();

    const lineOptions = ref(null);
    const lineData = reactive({
        labels: [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
        ],
        datasets: [
            {
                label: 'First Dataset',
                data: [65, 59, 80, 81, 56, 55, 40],
                fill: false,
                backgroundColor: '#2f4860',
                borderColor: '#2f4860',
                tension: 0.4,
            },
            {
                label: 'Second Dataset',
                data: [28, 48, 40, 19, 86, 27, 90],
                fill: false,
                backgroundColor: '#00bb7e',
                borderColor: '#00bb7e',
                tension: 0.4,
            },
            {
                label: 'Second Dataset 5',
                data: [28, 48, 32, 19, 86, 80, 90],
                fill: false,
                backgroundColor: '#00bb7e',
                borderColor: '#00bb7e',
                tension: 0.4,
            },
        ],
    });

    const applyLightTheme = () => {
        lineOptions.value = {
            plugins: {
                legend: {
                    labels: {
                        color: '#495057',
                    },
                },
            },
            scales: {
                x: {
                    ticks: {
                        color: '#495057',
                    },
                    grid: {
                        color: '#ebedef',
                    },
                },
                y: {
                    ticks: {
                        color: '#495057',
                    },
                    grid: {
                        color: '#ebedef',
                    },
                },
            },
        };
    };

    const applyDarkTheme = () => {
        lineOptions.value = {
            plugins: {
                legend: {
                    labels: {
                        color: '#ebedef',
                    },
                },
            },
            scales: {
                x: {
                    ticks: {
                        color: '#ebedef',
                    },
                    grid: {
                        color: 'rgba(160, 167, 181, .3)',
                    },
                },
                y: {
                    ticks: {
                        color: '#ebedef',
                    },
                    grid: {
                        color: 'rgba(160, 167, 181, .3)',
                    },
                },
            },
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

    onMounted(() => {
        if (isDarkTheme.value) {
            applyDarkTheme();
        } else {
            applyLightTheme();
        }
    });
</script>

<style lang="scss" scoped>
    .chart-container {
        width: 500px;
        /* Ajuste a largura conforme necessário */
        height: 300px;
        /* Ajuste a altura conforme necessário */
    }
</style>
