<template>
    <div :class="['card mb-0 status-card', statusClass]">
        <div class="flex justify-content-between mb-3">
            <div>
                <span class="block text-500 font-medium mb-3"> {{ titleText }} </span>
                <div class="text-900 font-medium text-xl">26000/ton</div>
            </div>
            <div class="flex align-items-center justify-content-center icon-container">
                <i :class="iconClass" class="pi text-2xl"></i>
            </div>
        </div>
        <div class="flex justify-content-between">
            <span :class="pdeClass">{{ pdeText }}</span>
            <span class="text-500">Hors: 12:80</span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { defineProps } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true
    },
    titelText: {
        type: String,
        required: true
    }
});

const titleText = computed(() => (props.titelText))
const statusClass = computed(() => (props.status === 'active' ? 'active' : 'inactive'));
const iconClass = computed(() => (props.status === 'active' ? 'pi-sitemap text-green-500' : 'pi-sitemap text-red-500'));
const pdeText = computed(() => (props.status === 'active' ? 'PDE: 100%' : 'PDE: 50%'));
const pdeClass = computed(() => (props.status === 'active' ? 'text-green-500' : 'text-red-500'));

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