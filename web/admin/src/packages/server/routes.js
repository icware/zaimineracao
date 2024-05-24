
const routesToservers = [
  {
    path: "/servers",
    meta: {
      title: "Servidores",
    },
    component: () => import('./views/ViewServer.vue'),
  },
];

export default routesToservers;
