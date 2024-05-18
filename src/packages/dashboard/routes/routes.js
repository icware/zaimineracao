const prefix = "/dashboard"; //Alterar prefixo

//Definir rotas
const Routers = [
  {
    path: "/",
    name: "dashboard",

    meta: {
      title: "Dashboard",
    },

    component: () =>
      import(/* webpackChunkName: "dashboard" */ "../views/DashboardView.vue"),
  },

  {
    path: "/profile",
    name: "dashProfile",

    meta: {
      title: "Profile",
    },

    component: () =>
      import(/* webpackChunkName: "dashProfile" */ "../views/DashProfileView.vue"),
  },
];

//Alterar nome / exportar
const DashRouters = Routers.map((route) => ({
  ...route,
  path: `${prefix}/${route.path}`,
  meta:route.meta
}));

export default DashRouters;
