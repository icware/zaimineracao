import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import registerPrimeVue from './prime';
import { useAuthStore } from '@/store/AuthStore';

import '@/assets/styles.scss';

const app = createApp(App);

app.use(router);
app.use(createPinia())
const authStore = useAuthStore();

authStore.validate();
authStore.applayTheme();

registerPrimeVue(app);

app.mount('#app');
