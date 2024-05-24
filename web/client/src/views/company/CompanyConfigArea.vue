<template>
    <Card>
        <template #title>Configurações</template>
        <template #subtitle>Primário</template>
        <template #content>
            <div class="card">

                <div class="grid flex justify-content-evenly   gap-5">

                    <div class="flex flex-column gap-2">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model="data.name" aria-describedby="name-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="capacity">Capacidade t/h</label>
                        <InputText id="capacity" v-model="data.capacity" aria-describedby="capacity-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="minimum">Mínima t/h</label>
                        <InputText id="minimum" v-model="data.minimum" aria-describedby="minimum-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="average">Média t/h</label>
                        <InputText id="average" v-model="data.average" aria-describedby="average-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="order">Ordem de exibição</label>
                        <InputNumber v-model="data.order" inputId="minmax-buttons" mode="decimal" showButtons :min="0"
                            :max="100" />
                    </div>
                </div>
            </div>
            <div class="flex flex-column  gap-2 ml-4">
                <div class="flex gap-2">
                    <Checkbox v-model="data.visible" binary variant="filled" />
                    <label class="md:fs-6 " for="visible">Área Visível</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.active" binary variant="filled" />
                    <label for="active">Ativo</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.main" binary variant="filled" />
                    <label for="main">Área Principal</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.primary" binary variant="filled" />
                    <label for="primary">Área Primário</label>
                </div>
            </div>

        </template>
    </Card>
</template>

<script setup>
import { ref } from 'vue';

const data = ref({
    name: ref(''),
    capacity: ref(''),
    minimum: ref(''),
    average: ref(''),
    order: ref(0),
    visible: ref(false),
    active: ref(false),
    main: ref(false),
    primary: ref(false),
});

</script>