<script setup>
import { computed, ref, } from 'vue';

const props = defineProps({
    data: {
        type: Array,
        required: true,
    }
})

const { data } = props

</script>
<template>
    <div class="card">
        {{ company }}
    </div>
    <div class="flex flex-column md:flex-row md:justify-content-start md:align-items-center">

        <div class="w-full sm:w-auto">
            <Button label="Salvar" icon="pi pi-save" class="p-button-text" @click="deletecompany" />
        </div>

        <div class="w-full sm:w-auto">
            <Button label="Refresh" icon="pi pi-refresh" class="p-button-text" @click="deletecompany" />
        </div>
        <div class="w-full sm:w-auto">
            <Button label="Novo" icon="pi pi-plus" class="p-button-text" @click="deletecompany" />
        </div>
    </div>
</template>