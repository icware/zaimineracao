<script setup>
import { onMounted, reactive, ref } from 'vue';
import DisplayCompany from '@/components/DisplayCompany.vue';
import { CompanyService } from '@/service/company/CompanyService';
import { useAuthStore } from '@/store/AuthStore';

const auth = useAuthStore();
const companyService = new CompanyService();

const data = ref([]);

async function getCompany() {
    if (auth.getIsAuth) {
        const response = await companyService.getCompany();
        data.value = response.data;
    }
}

onMounted(() => {
    getCompany();
});

</script>

<template>

    <div class="grid">
<<<<<<< HEAD
        <DisplayCompany v-for="(company, index) in data.companies" :key="index" :id="company.id" :code="company.code"
            :name="company.name" />
=======
        <DisplayCompany v-for="(data, index) in jsonData" :key="index" v-bind="data" />
    </div>
    <br>
    <div class="grid  ">
        <ChartInline />
        <ChartBar />


        <TableInstant />
        <TableTime />

>>>>>>> 7deb039bba3d754a2ef4bdc7217b20e9218603b9
    </div>

</template>
