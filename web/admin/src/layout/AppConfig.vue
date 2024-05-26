<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePrimeVue } from 'primevue/config';
import { useLayout } from '@/layout/composables/layout';
import { AuthService } from '@/service/auth/AuthService';
import AppThemes from '@/layout/AppThemes.vue';

const authService = new AuthService();


defineProps({
    simple: {
        type: Boolean,
        default: false
    }
});

const $primevue = usePrimeVue();
const inputStyle = computed(() => $primevue.config.inputStyle || 'outlined');

// Fontes disponiveis
const scales = ref([12, 13, 14, 15, 16]);
const visible = ref(false);
const inputStyles = ref([
    { label: 'Outlined', value: 'outlined' },
    { label: 'Filled', value: 'filled' }
]);
const menuModes = ref([
    { label: 'Estático', value: 'static' },
    { label: 'Sobreposto', value: 'overlay' }
]);
const compactMaterial = ref(false);
const primaryFocusRing = ref(true);

const { setScale, layoutConfig } = useLayout();

const onConfigButtonClick = () => {
    visible.value = !visible.value;
};
const onChangeTheme = (theme, mode) => {
    $primevue.changeTheme(layoutConfig.theme.value, theme, 'theme-css', () => {
        layoutConfig.theme.value = theme;
        layoutConfig.darkTheme.value = mode;
    });
    authService.setTheme({
        theme: theme,
        darkTheme: mode,
    });
};

const decrementScale = () => {
    setScale(layoutConfig.scale.value - 1);
    applyScale();
};
const incrementScale = () => {
    setScale(layoutConfig.scale.value + 1);
    applyScale();
};
const applyScale = () => {
    document.documentElement.style.fontSize = layoutConfig.scale.value + 'px';
    authService.setTheme(
        { scale: layoutConfig.scale.value, }
    );
};
const onInputStyleChange = (value) => {
    $primevue.config.inputStyle = value;
    authService.setTheme(
        { inputStyle: value, }
    );
};
const onMenuModeChange = (value) => {
    layoutConfig.menuMode.value = value;
    authService.setTheme({
        menuMode: value,
    });
};
const onRippleChange = (value) => {
    layoutConfig.ripple.value = value;
    authService.setTheme(
        { ripple: value, }
    );
};
const onDarkModeChange = (value) => {
    const newThemeName = value ? layoutConfig.theme.value.replace('light', 'dark') : layoutConfig.theme.value.replace('dark', 'light');

    layoutConfig.darkTheme.value = value;
    onChangeTheme(newThemeName, value);
};
const changeTheme = (theme, color) => {
    let newTheme, dark;

    newTheme = theme + '-' + (layoutConfig.darkTheme.value ? 'dark' : 'light');

    if (color) {
        newTheme += '-' + color;
    }

    if (newTheme.startsWith('md-') && compactMaterial.value) {
        newTheme = newTheme.replace('md-', 'mdc-');
    }

    dark = layoutConfig.darkTheme.value;

    onChangeTheme(newTheme, dark);
};
const isThemeActive = (themeFamily, color) => {
    let themeName;
    let themePrefix = themeFamily === 'md' && compactMaterial.value ? 'mdc' : themeFamily;

    themeName = themePrefix + (layoutConfig.darkTheme.value ? '-dark' : '-light');

    if (color) {
        themeName += '-' + color;
    }

    return layoutConfig.theme.value === themeName;
};
const onCompactMaterialChange = (value) => {
    compactMaterial.value = value;

    if (layoutConfig.theme.value.startsWith('md')) {
        let tokens = layoutConfig.theme.value.split('-');

        changeTheme(tokens[0].substring(0, 2), tokens[2]);
    }
};
const onFocusRingColorChange = (value) => {
    primaryFocusRing.value = value;
    let root = document.documentElement;

    if (value) {
        if (layoutConfig.darkTheme.value) root.style.setProperty('--p-focus-ring-color', 'var(--primary-500)');
        else root.style.setProperty('--p-focus-ring-color', 'var(--primary-500)');
    } else {
        if (layoutConfig.darkTheme.value) root.style.setProperty('--p-focus-ring-color', 'var(--surface-500)');
        else root.style.setProperty('--p-focus-ring-color', 'var(--surface-900)');
    }
};

const themeDialog = ref(false);
const submitted = ref(false);

const openThemes = () => {
    themeDialog.value = true;
    submitted.value = false;
};

const hideThemes = () => {
    themeDialog.value = false;
    submitted.value = false;
};

const editThemes = () => {
    themeDialog.value = true;
};


</script>

<template>
    <button class="layout-config-button p-link" type="button" @click="onConfigButtonClick()">
        <i class="pi pi-cog"></i>
    </button>

    <Sidebar v-model:visible="visible" position="right" class="layout-config-sidebar w-26rem" pt:closeButton="ml-auto">
        <div class="p-2">
            <Button icon="pi pi-pencil" label="Aparencia" class="mr-2" severity="primary" rounded
                @click="editThemes()" />

        </div>
    </Sidebar>

    <Dialog v-model:visible="themeDialog" :style="{ width: '70vw' }" header="Estilos" :modal="true"
        class="p-fluid layout-config-sidebar text-xl font-semibold w-30rem">

        <AppThemes />

        <template #footer>
            <div class="flex flex-column md:flex-row md:justify-content-end md:align-items-center">
                <div class="w-full sm:w-auto mr-2">
                    <Button icon="pi pi-times" severity="danger" label="Fechar Janelas" @click="hideThemes" />
                </div>
                <div class="w-full sm:w-auto">
                    <Button icon="pi pi-save" severity="primary" label="Salvar Alterações" @click="hideThemes" />
                </div>
            </div>
        </template>

    </Dialog>

</template>

<style lang="scss" scoped></style>
