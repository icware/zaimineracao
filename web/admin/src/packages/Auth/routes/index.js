
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
      import("../views/AuthRegister.vue"),
  },
  {
    path: "/user/verify",
    meta: {
      title: "Verificar email",
    },
    component: () =>
      import("../views/VerifyUser.vue"),
  },
];

export default AuthRouters;
