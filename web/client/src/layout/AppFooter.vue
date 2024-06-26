<script setup>
    import { useLayout } from '@/layout/composables/layout';
    import { computed } from 'vue';

    const { layoutConfig } = useLayout();

    const logoUrl = computed(() => {
        return `/layout/images/${
            layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'
        }.svg`;
    });
</script>

<template>
    <div class="layout-footer">
        <!-- <img src="/images/logo.jpg" alt="Logo" height="20" class="mr-2" /> -->
        <span class="font-medium ml-2">Seja bem vindo!</span>
    </div>
</template>
<style lang="scss" scoped></style>
