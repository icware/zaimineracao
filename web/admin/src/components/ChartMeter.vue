<template>
    <div id="chart_gauge"></div>
</template>

<script setup>
import { onMounted, watch } from 'vue';

// Define props
const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});

const updateMeter = (data) => {
    if (data.meter) {
        const meterPercent = data.meter.value;
        const meterMin = data.meter.min;
        const meterMed = data.meter.med;
        const meterArea = data.meter.area;
        const meterValue = parseInt(meterPercent.replace("%", ""));

        google.charts.load("current", { packages: ["gauge"] });

        google.charts.setOnLoadCallback(() => {
            const dataTable = new google.visualization.DataTable();
            dataTable.addColumn("string", "Label");
            dataTable.addColumn("number", meterArea);
            dataTable.addRows([["PDE", meterValue]]);

            const options = {
                width: calculateHeight(90),
                height: calculateHeight(90),
                redFrom: 0,
                redTo: meterMin,
                yellowFrom: meterMin,
                yellowTo: meterMed,
                greenFrom: meterMed,
                greenTo: 100,
                minorTicks: 5,
            };

            const chart = new google.visualization.Gauge(
                document.getElementById("chart_gauge")
            );

            chart.draw(dataTable, options);
        });
    } else {
        console.log("Setor principal não definido");
    }
};

// Dummy function for calculateHeight, replace with actual implementation
const calculateHeight = (value) => value;

// Watch props.data changes and update the meter
watch(() => props.data, () => {
    updateMeter(props.data);
}, { immediate: true });

onMounted(() => {
    updateMeter(props.data);
});
</script>