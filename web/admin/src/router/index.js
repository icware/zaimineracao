import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../store/AuthStore";
import AuthRouters from "@/packages/Auth/routes";
import AppLayout from "@/layout/AppLayout.vue";
import routesToservers from "@/packages/server/routes";
import routesToUsers from "@/packages/user/routes";
import routesToCompanies from "@/packages/company/routes";
import routesToservices from "@/packages/service/RoutesService";

const routes = [
  {
    path: "/",
    component: AppLayout,
    meta: {
      title: "Admin Zai",
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
      ...routesToservers,
      ...routesToUsers,
      ...routesToCompanies,
      ...routesToservices,
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

  const authStore = useAuthStore();

  if (!to.meta.unprotected) {
    if (!authStore.authenticated) {
      next("/login");
    } else {
      if (!authStore.verified && to.path !== '/user/verify') {
        console.log(authStore.verified);
        next("/user/verify");
      } else {
        next();
      }
    }

  } else {
    next();
  }
});

export default router;
