
const AuthRouters = [
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

export default AuthRouters;
