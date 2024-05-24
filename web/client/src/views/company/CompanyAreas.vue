<template>
    <Card>
        <template #title>Pedreira Guapo</template>
        <template #content>
            <div class="card">
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
    </Card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { ProductService } from '@/service/ProductService';


// Falta linkar com o servidor para trazer os dados reais
const addArea = ref([
    { name: 'Teste 1', value: '350.00', visible: 'Sim', button: [] },
    { name: 'Teste 2', value: '150.00', visible: 'Não', button: [] }
]
);


onMounted(async () => {
    products.value = await ProductService.getProductsMini();
});
</script>