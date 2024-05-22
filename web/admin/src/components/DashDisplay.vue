<template>
    <div v-if="visible" class="col-12 lg:col-2 xl:col-auto mb-0">
        <div :class="['card mb-0 status-card']">
            <div class="flex justify-content-between mb-0">
                <div>
                    <span class="block text-500 font-medium mb-0"> {{ name }}</span>
                    <div class="text-900 font-medium text-xl">{{ value }}</div>
                </div>
                <div class="flex align-items-center justify-content-center icon-container">
                    <i :class="iconClass" class="pi text-2xl"></i>
                </div>
            </div>
            <div class="flex justify-content-between">
                <span :class="pdeClass">{{ pde }}</span>
                <span class="text-500">{{ life }}</span>
            </div>
        </div>
    </div>

</template>

<script setup>
import { computed, ref, defineProps } from 'vue';

const props = defineProps({
    visible: {
        type: Boolean,
        required: true
    },
    status: {
        type: Boolean,
        required: true
    },
    name: {
        type: String,
        required: true
    },
    value: {
        type: String,
        required: true,
        default: "0.00 ton"
    },
    pde: {
        type: String,
        required: true,
        default: "0 %"
    },
    life: {
        type: String,
        required: true,
        default: "00:00"
    },
});

const statusClass = computed(() => (props.status ? 'true' : 'false'));
const iconClass = computed(() => (props.status ? 'pi-sitemap text-green-500' : 'pi-sitemap text-red-500'));
const { name, value, pde, life } = props;

</script>

<style scoped></style>