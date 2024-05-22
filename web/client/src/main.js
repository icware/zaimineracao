import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import registerPrimeVue from './prime';
import checkAuth from './auth';

const app = createApp(App);

app.use(router);
app.use(createPinia())

checkAuth();

registerPrimeVue(app);

app.mount('#app');
