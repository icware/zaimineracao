
const routesToUsers = [
  {
    path: "/users",
    meta: {
      title: "Usuários",
    },
    component: () => import('./views/ViewUser.vue'),
  },
];

export default routesToUsers;
