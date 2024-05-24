
const routesToCompanies = [
  {
    path: "/companies",
    meta: {
      title: "Empresas",
    },
    component: () => import('./views/ViewCompany.vue'),
  },
];

export default routesToCompanies;
