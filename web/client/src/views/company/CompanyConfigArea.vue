<template>
    <Card>
        <template #title>Configurações</template>
        <template #subtitle>{{ companyName.name }}</template>
        <template #content>
            <div class="card">
                <div class="grid flex justify-content-evenly gap-5">
                    <div class="flex flex-column gap-2">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model="data.name" aria-describedby="name-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="max">Capacidade t/h</label>
                        <InputText id="max" v-model="data.max" aria-describedby="accumulated-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="med">Média t/h</label>
                        <InputText id="average" v-model="data.med" aria-describedby="average-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="min">Mínima t/h</label>
                        <InputText id="minimum" v-model="data.min" aria-describedby="minimum-help" />
                    </div>

                    <div class="flex flex-column gap-2">
                        <label for="order">Ordem de exibição</label>
                        <InputNumber v-model="data.order" inputId="minmax-buttons" mode="decimal" showButtons :min="0" :max="100" />
                    </div>
                </div>
            </div>

            <div class="grid flex flex-row justify-content-center gap-4 mt-5">
                <div class="flex gap-2">
                    <Checkbox v-model="data.active" binary variant="filled" />
                    <label for="active">Ativo</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.visible" binary variant="filled" />
                    <label class="md:fs-6" for="visible">Visível</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.main" binary variant="filled" />
                    <label for="main">Principal</label>
                </div>
                <div class="flex gap-2">
                    <Checkbox v-model="data.primary" binary variant="filled" />
                    <label for="primary">Primário</label>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 justify-content-center mt-5">
                <Button label="Salvar" @click="show()" />
                <Button label="Cancelar" class="p-button-secondary" @click="cancel()" />
            </div>
        </template>
    </Card>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useToast } from 'primevue/usetoast';
    import { useCompanyStore } from '../../store/CompanyStore';

    const { getAreaData, getCompanyData } = useCompanyStore();
    const toast = useToast();
    const router = useRouter();
    const route = useRoute();
    const companyName = ref({});

    const data = ref({
        name: '',
        max: '',
        min: '',
        med: '',
        visible: false,
        active: false,
        main: false,
        primary: false,
        order: 0,
    });

    const cancel = () => {
        router.push('/company/areas');
    };

    const show = () => {
        toast.add({
            severity: 'info',
            summary: 'Info',
            detail: 'Mensagem de Teste',
            life: 3000,
        });
    };

    const fetchAreaData = async (id) => {
        try {
            const areaData = getAreaData(id);

            if (areaData) {
                const accumulatedValue = parseFloat(areaData.accumulated.value);
                const minCapacity = parseFloat(areaData.timers?.min_capacity || '0');
                const medCapacity = parseFloat(areaData.timers?.med_capacity || '0');
                const maxCapacity = parseFloat(areaData.timers?.max_capacity || '0');

                const min = ((accumulatedValue * minCapacity) / 100).toFixed(2);
                const med = ((accumulatedValue * medCapacity) / 100).toFixed(2);
                const max = ((accumulatedValue * maxCapacity) / 100).toFixed(2);

                data.value = {
                    name: areaData.name,
                    min: min,
                    med: med,
                    max: max,
                    visible: areaData.visible,
                    active: areaData.active,
                    main: areaData.main,
                    // primary: areaData.primary,
                    order: areaData.order || 0,
                };
            }

            const companyData = await getCompanyData;

            if (companyData && companyData.company) {
                companyName.value = {
                    name: companyData.company.name + ' / ' + route.name,
                };
            }
        } catch (error) {
            console.error('Error fetching area data:', error);
        }
    };

    onMounted(() => {
        const areaId = parseInt(route.params.areaId, 10);
        fetchAreaData(areaId);
    });
</script>

<style>
    /* Adicione qualquer estilo personalizado aqui */
</style>
