<template>

    <div class="grid">
        <DisplayHeader v-for="(data, index) in jsonData" :key="index" v-bind="data" />
    </div>
</template>

<script setup>
import DisplayHeader from '@/components/DisplayHeader.vue';
import TableInstance from '@/componets/TableInstance.vue';
import TableTime from '@/components/TableTime.vue'

const jsonData = [
    {
        "status": true,
        "title": "Primário",
        "value": "26000",
        "pde": "50%",
        "life": "12:80",
        "life_run": "12:80",
        "life_shift": "12:80",
    },
    {
        "status": false,
        "title": "Secundário",
        "value": "12000",
        "pde": "75%",
        "life": "08:30",

    }
];

</script>

<style lang="scss" scoped></style>