<template>
    <div class="card">
        <h1>Pedreira Guapo</h1>
        <DataTable :value="addArea" tableStyle="min-width: 50rem">
            <Column field="name" header="Área"></Column>
            <Column field="value" header="Capacidade t/h"></Column>
            <Column field="visible" header="Visibilidade"></Column>
            <Column field="options" header="Opções">
                <template #body="{ data }">
                    <Button label="Configurar" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { ProductService } from '@/service/ProductService';


// Falta linkar com o servidor para trazer os dados reais
const addArea = ref([
    { name: 'Primário', value: '350.00', visible: 'Sim', button: [] },
    { name: 'Brita 1', value: '150.00', visible: 'Não', button: [] }
]
);


onMounted(async () => {
    products.value = await ProductService.getProductsMini();
});
</script>