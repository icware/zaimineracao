import { toRefs, reactive, computed, watch } from 'vue';


// Carregar configuração do localStorage ou usar padrão
const savedLayoutConfig = JSON.parse(localStorage.getItem('layoutConfig')) || {
    ripple: true,
    darkTheme: true,
    inputStyle: 'outlined',
    menuMode: 'static',
    theme: 'aura-dark-green',
    scale: 12,
    activeMenuItem: null,
};

// const layoutConfig = reactive({
//     ripple: true,
//     darkTheme: true,
//     inputStyle: 'outlined',
//     menuMode: 'static',
//     theme: 'aura-dark-gree',
//     scale: 12,
//     activeMenuItem: null,
// });


const layoutConfig = reactive(savedLayoutConfig);

const layoutState = reactive({
    staticMenuDesktopInactive: false,
    overlayMenuActive: false,
    profileSidebarVisible: false,
    configSidebarVisible: false,
    staticMenuMobileActive: false,
    menuHoverActive: false
});

// Salvar layoutConfig no localStorage sempre que mudar
watch(layoutConfig, (newConfig) => {
    localStorage.setItem('layoutConfig', JSON.stringify(newConfig));
}, { deep: true });

export function useLayout() {
    const setScale = (scale) => {
        layoutConfig.scale = scale;
    };

    const setActiveMenuItem = (item) => {
        layoutConfig.activeMenuItem = item.value || item;
    };

    const onMenuToggle = () => {
        if (layoutConfig.menuMode === 'overlay') {
            layoutState.overlayMenuActive = !layoutState.overlayMenuActive;
        }

        if (window.innerWidth > 991) {
            layoutState.staticMenuDesktopInactive = !layoutState.staticMenuDesktopInactive;
        } else {
            layoutState.staticMenuMobileActive = !layoutState.staticMenuMobileActive;
        }
    };

    const isSidebarActive = computed(() => layoutState.overlayMenuActive || layoutState.staticMenuMobileActive);

    const isDarkTheme = computed(() => layoutConfig.darkTheme);

    return { layoutConfig: toRefs(layoutConfig), layoutState: toRefs(layoutState), setScale, onMenuToggle, isSidebarActive, isDarkTheme, setActiveMenuItem };
}
