<template>

    <div class="grid">
        <DisplayCompany v-for="(data, index) in jsonData" :key="index" v-bind="data" />
    </div>
    <br>
    <div class="grid  ">
        <ChartInline />
        <ChartBar />


        <TableInstant />
        <TableTime />

    </div>

</template>

<script setup>
import DisplayCompany from '@/components/DisplayCompany.vue';
import ChartInline from '../../components/ChartInline.vue';
import ChartBar from '../../components/ChartBar.vue';


import TableInstant from '../../components/TableInstant.vue';
import TableTime from '../../components/TableTime.vue';




const jsonData = [
    {
        "name": "Nome da Empresa",
        "levelUser": "Administrador",
        "code": "1000",
    },
    {
        "name": "Nome da Empresa",
        "levelUser": "Administrador",
        "code": "2000",
    },
    {
        "name": "Nome da Empresa",
        "levelUser": "Administrador",
        "code": "3000",
    },
    {
        "name": "Nome da Empresa",
        "levelUser": "Administrador",
        "code": "4000",
    },
];


</script>
