<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }]
    },
    {
        label: 'Usuário',
        items: [
            { label: 'Unidades', icon: 'pi pi-building', to: '/companies' },
            { label: 'Áreas', icon: 'pi pi-chart-bar', to: '/company/areas' },
            { label: 'Lista de Usuários', icon: 'pi pi-users', to: '/company/areas/users' },
            { label: 'Detalhes', icon: 'pi pi-file', to: '/company/detail' },
            { label: 'Configurações', icon: 'pi pi-cog', to: '/company/config' }
        ]

    }

]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
        <li>

        </li>
    </ul>
</template>

<style lang="scss" scoped></style>
