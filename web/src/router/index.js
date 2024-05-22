import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../store/AuthStore";
import AuthRouters from "@/packages/Auth/routes";
import AppLayout from "@/layout/AppLayout.vue";

const routes = [
  {
    path: "/",
    component: AppLayout,
    meta: {
      title: "Zai Mineração",
    },
    children: [
      {
        path: "/dashboard",
        name: "dashboard",
        meta: {
          title: "Dashboard",
        },
        component: () => import("@/views/DashboardView.vue"),
      },
      {
        path: "/companies",
        name: "companies",
        meta: {
          title: "Unidades",
        },
        component: () => import("@/views/company/CompanyList.vue"),
      },
      {
        path: "/company/areas",
        name: "companyAreas",
        meta: {
          title: "Áreas",
        },
        component: () => import("@/views/company/CompanyList.vue"),
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
    auth.getIsAuth ? next() : next("/login");
  } else {
    next();
  }
});

export default router;
