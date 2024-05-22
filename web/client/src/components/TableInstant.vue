<template>
    <div class="col-12 xl:col-6">
        <h5>Produção Instantânea</h5>
        <DataTable :value="dd" :rows="5" :paginator="true" responsiveLayout="scroll">
            <Column field="name" header="Área" :sortable="true" style="width: 35%"></Column>
            <Column field="name" header="Inicio" :sortable="true" style="width: 35%"></Column>
            <Column field="name" header="Final" :sortable="true" style="width: 35%"></Column>
            <Column field="name" header="Vazão" :sortable="true" style="width: 35%"></Column>
            <Column field="price" header="Pde" :sortable="true" style="width: 35%"></Column>
            <Column field="price" header="Status" :sortable="true" style="width: 35%"></Column>
        </DataTable>

    </div>
</template>

<script setup>

</script>
