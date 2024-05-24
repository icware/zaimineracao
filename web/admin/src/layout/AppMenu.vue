<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Dashboard',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }]
    },
    {
        label: 'Sistema',
        items: [
            { label: 'Servidores', icon: 'pi pi-fw pi-server', to: '/servers' },
            { label: 'Serviços', icon: 'pi pi-fw pi-cog', to: '/' },

        ]

    },
    {
        label: 'Cliente',
        items: [
            { label: 'Usuários', icon: 'pi pi-fw pi-users', to: '/users' },
            { label: 'Empresas', icon: 'pi pi-fw pi-building', to: '/companies' },
        ]

    },

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
