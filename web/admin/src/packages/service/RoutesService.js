import ViewService from './views/ViewService.vue'

const routesToservices = [
  {
    path: "/services",
    component: ViewService,
    meta: {
      title: "Serviços",
    },
    children: [

    ]
  },
];

export default routesToservices;
