<template>
    <div class="col-5">
        <div class="card">
            <h5>Desempenho</h5>
            <div class="chart-container">
                <Chart type="bar" :data="barData" :options="barOptions" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import { useLayout } from '@/layout/composables/layout';

const barOptions = ref(null);
const barData = reactive({
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'First Dataset',
            data: [65, 59, 80, 81, 56, 55, 40],
            backgroundColor: '#2f4860',
            borderColor: '#2f4860',
        },
        {
            label: 'Second Dataset',
            data: [28, 48, 40, 19, 86, 27, 90],
            backgroundColor: '#00bb7e',
            borderColor: '#00bb7e',
        }
    ]
});

const applyLightTheme = () => {
    barOptions.value = {
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
    barOptions.value = {
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

// Verifica o tema e aplica as opções correspondentes
const { isDarkTheme } = useLayout();
watch(isDarkTheme, (newValue) => {
    if (newValue) {
        applyDarkTheme();
    } else {
        applyLightTheme();
    }
});

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
