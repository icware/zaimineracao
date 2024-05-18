// const prefix = '/auth';

const AuthRoutes = [
  {
    path: "/login",
    name: "authLogin",

    meta: {
      unprotected: true,
      title: "Login",
    },
    component: () =>
      import(/* webpackChunkName: "autLogin" */ "../views/AuthLogin.vue"),
  },

  {
    path: "/register",
    name: "authRegister",

    meta: {
      unprotected: true,
      title: "Cadastro",
    },

    component: () =>
      import(/* webpackChunkName: "autRegister" */ "../views/AuthRegister.vue"),
  },
];

// const AuthRoutes = Routers.map(route => ({
//   ...route,
//   path:`${prefix}/${route.path}`,
//   meta:route.meta,
// }));

export default AuthRoutes;
