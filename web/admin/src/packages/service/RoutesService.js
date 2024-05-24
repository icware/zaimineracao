import ViewService from './views/ViewService.vue'

const routeServices = [
    {
      path: "/services",
      component:ViewService,
        meta: {
        title: "Serviços",
      },
      children: [
            
      ]
    },   
  ];
  
  export default routeServices;
  