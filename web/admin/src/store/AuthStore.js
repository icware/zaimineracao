import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import { StoreService } from '../service/StoreService';

const useStoreService = new StoreService();

export const useAuthStore = defineStore('auth', () => {
    const token = ref(useStoreService.getValue('token'));  
    const user = ref(useStoreService.getJson('user'));
    const isAuth = ref(false);

    function setToken(value) {
        if (value) {
         useStoreService.setValue('token', value);    
         token.value = value;
         setIsAuth(true);
        } else {
          console.log('Token não foi salvo');
        }
      }
    
      function setUser(value) {
        if (value) {
          useStoreService.setJson('user', value);
          user.value = value;
        } else {
          console.log('Usuário não foi salvo');
        }
      }
   
      function setIsAuth(value) {        
        if (value) {
          isAuth.value = value;
        } else {
          isAuth.value = false;          
        }
      }

      function authLogout() {
        useStoreService.del('token');
        useStoreService.del('user');
        token.value = null;
        user.value = null;
        isAuth.value = false;
        window.location.href = '/login';
        console.log('Logout')
      }


      const getUser = computed(() => {        
        return user.value;
      });
    
      const getToken = computed(() => {
        return token.value;
      });
    
    
      const getIsAuth = computed (
        () => {
          return isAuth.value;
        }
      );

      return {
        setToken,
        setUser,
        setIsAuth,
        authLogout,
        getToken,
        getUser,
        getIsAuth,
      }

} );
