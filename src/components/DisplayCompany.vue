<template>
    <div :class="['card mb-0 status-card']">
        <div class="flex justify-content-between mb-3">
            <div>
                <span class="block text-xl font-medium mb-3"> {{ name }} </span>
                <div class="text-900 font-medium text-500">{{ code }}</div>
            </div>
            <div class="flex align-items-center justify-content-center icon-container">
                <i class="pi pi-building text-2xl"></i>
            </div>
        </div>
        <div class="flex-column ">
            <span class="text-500">
                <Button @click="selectCompany(code)">Selecionar</Button>
            </span>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { StoreService } from '@/service/StoreService';
import { useRouter } from 'vue-router';

const useStoreService = new StoreService();
const router = useRouter();

function selectCompany(code) {
    useStoreService.setValue('company', code);
    router.push('/dashboard');
}

const props = defineProps({
    id: {
        type: Number,
        required: true
    },
    code: {
        type: String,
        required: true
    },
    name: {
        type: String,
        required: true
    }
});


</script>


<style scoped>
.status-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 16px;
    margin: 8px;
    width: 300px;
}

.status-card.active {
    border-color: green;
}

.status-card.inactive {
    border-color: red;
}

.icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
}
</style>