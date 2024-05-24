import { useLayout } from '@/layout/composables/layout';

export const apiSettgins = {
    // address: 'https://api.zaimineracao.com.br',
    address: 'http://127.0.0.1:8000',
    base: null,
    version: null,
    type: {
        token: 'Bearer'
    }
}

export const secretKey = 'qt60O30OK07gNdmdyQoYG2EfLL2K8vjEkcBML4nooN7C06cshx4W1rKKfKwX1yb0';

export function setTheme() {
    const { layoutConfig, setScale } = useLayout();
    const savedConfig = JSON.parse(localStorage.getItem('layoutConfig'));

    if (savedConfig) {
        layoutConfig.value = savedConfig;

        const themeLink = document.getElementById('theme-css');
        if (themeLink) {
            themeLink.href = `/themes/${savedConfig.theme}/theme.css`;
        }
        document.documentElement.style.fontSize = `${savedConfig.scale}px`;
    }
}