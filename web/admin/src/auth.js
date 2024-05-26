// import { useAuthStore } from './store/AuthStore';
// import { AuthService } from '@/service/auth/AuthService';

// import { useLayout } from '@/layout/composables/layout';
// export function setTheme() {
//     const { layoutConfig, setScale } = useLayout();
//     const authStore = useAuthStore();
//     if (authStore.authenticated) {
//         const savedConfig = authStore.theme;
//         if (savedConfig) {
//             layoutConfig.value = savedConfig;
//             const themeLink = document.getElementById('theme-css');
//             if (themeLink) {
//                 themeLink.href = `/themes/${savedConfig.theme}/theme.css`;
//             }
//             document.documentElement.style.fontSize = `${savedConfig.scale}px`;
//         }
//     }
// }

// export function checkAuth() {
//     if (localStorage.getItem('token')) {
//         (async () => {
//             const auth = useAuthStore();
//             const authService = new AuthService();
//             try {
//                 auth.setIsAuth(true);
//                 await authService.check();
//             } catch (error) {
//                 auth.setIsAuth(false);
//                 auth.authLogout();
//                 console.log(error.message);
//             }

//         })()
//     }

// }