<template>

    <div class="grid">
        <DisplayCompany v-for="(data, index) in jsonData" :key="index" v-bind="data" />
    </div>
</template>

<script setup>
import DisplayCompany from '@/components/DisplayCompany.vue';

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

<style lang="scss" scoped></style>