<template>
    <div :class="['card mb-0 status-card']">
        <div class="flex justify-content-between mb-3">
            <div>
                <span class="block text-xl font-medium mb-3"> {{ titleCompany }} </span>
                <div class="text-900 font-medium text-500">{{ code }}</div>
            </div>
            <div class="flex align-items-center justify-content-center icon-container">
                <i class="pi pi-building text-2xl"></i>
            </div>
        </div>
        <div class="flex-column ">
            <span :class="codeClass">{{ levelUser }}</span>
            <span class="text-500">
                <Dropdown v-model="selectedCity" :options="cities" optionLabel="name" placeholder="Seleciona a opção"
                    class="w-full md:w-14rem mt-5  " />
            </span>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps, ref } from 'vue';


const selectedCity = ref();
const cities = ref([
    { name: 'Monitor BI' },
    { name: 'Detalhe' },
    { name: 'Configurar' },
    { name: 'Area' },
    { name: 'Usuários' }
]);

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    levelUser: {
        type: String,
        required: true
    },
    code: {
        type: String,
        required: true
    }
});


const titleCompany = computed(() => props.name);
const levelUser = computed(() => props.levelUser);

// Ajustando a lógica para code e codeClass
const codeClass = computed(() => (props.code ? 'text-green-500' : 'text-red-500'));
const code = computed(() => props.code);

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