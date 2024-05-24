import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../store/AuthStore";
import AuthRouters from "@/packages/Auth/routes";
import AppLayout from "@/layout/AppLayout.vue";
import routesToservers from "@/packages/server/routes";
import routesToUsers from "@/packages/user/routes";
import routesToCompanies from "@/packages/company/routes";
// import routeService from "@/packages/service/RoutesService";

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
      // ...routeService,  
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
