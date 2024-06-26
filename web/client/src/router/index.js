import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/AuthStore';
import AuthRouters from '@/packages/Auth/routes';
import AppLayout from '@/layout/AppLayout.vue';

const routes = [
    {
        path: '/',
        component: AppLayout,
        meta: {
            title: 'Zai Mineração',
        },
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                meta: {
                    title: 'Dashboard',
                },
                component: () => import('@/views/DashboardView.vue'),
            },
            {
                path: '/companies',
                name: 'companies',
                meta: {
                    title: 'Unidades',
                },
                component: () => import('@/views/company/CompanyList.vue'),
            },
            {
                path: '/company/areas/users',
                name: 'companyAreasUsers',
                meta: {
                    title: 'Company | Users',
                },
                component: () => import('@/views/company/CompanyListUsers.vue'),
            },
            {
                path: '/company/config',
                name: 'companyConfig',
                meta: {
                    title: 'Company | Settings',
                },
                component: () => import('@/views/company/CompanyConfig.vue'),
            },
            {
                path: '/company/areas',
                name: 'Áreas',
                meta: {
                    title: 'Company | Áreas',
                },
                component: () => import('@/views/company/CompanyAreas.vue'),
            },
            {
                path: '/company/areas/config/:areaId',
                name: 'Config-Áreas',
                meta: {
                    title: 'Áreas | Settings',
                },
                component: () =>
                    import('@/views/company/CompanyConfigArea.vue'),
            },
            {
                path: '/company/detail',
                name: 'companyDetail',
                meta: {
                    title: 'Detalhes de Produção',
                },
                component: () => import('@/views/company/CompanyDetail.vue'),
            },
        ],
    },
    ...AuthRouters,
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    document.title = to.meta.title;

    const auth = useAuthStore();

    if (!to.meta.unprotected) {
        auth.getIsAuth ? next() : next('/login');
    } else {
        next();
    }
});

export default router;
