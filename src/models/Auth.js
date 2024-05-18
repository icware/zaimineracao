import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { setStorage, setJsonStorage, delStorage, getStorage, getJsonStorage } from "@/service/Storage";
import { AuthCheck } from "@/controllers/AuthController";


const useAuthModel = defineStore('auth', () => {
    const token = ref(getStorage('token'));  
    const user = ref(getJsonStorage('user'));
    const isAuth = ref(false);

    function setData(response){
      authLogout();
      setToken(response.token);
      setUser(response.data.user);
      setIsAuth(true);
    }

    function setToken(value) {
        if (value) {
         setStorage("token", value);    
         token.value = value;
        } else {
          console.log("Token não foi salvo");
        }
      }
    
      function setUser(value) {
        if (value) {
          setJsonStorage("user", value);
          user.value = value;
        } else {
          console.log("Usuário não foi salvo");
        }
      }
   
      function setIsAuth(value) {        
        if (value) {
          isAuth.value = value;
        } else {
          isAuth.value = false;          
        }
      }

      async function checkToken() {
        try {
           const  isValid = await AuthCheck();
           if (!isValid) {
              authLogout();
           }
        } catch (error) {
          console.log(error.message);
          throw new Error(error)
        }
        
      }

      function authLogout() {
        delStorage('token');
        delStorage('user');
        token.value = null;
        user.value = null;
        isAuth.value = false;
        window.location.href = "/";
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
        setData,
        setToken,
        setUser,  
        setIsAuth,
        authLogout,
        checkToken,
        getToken,
        getUser,
        getIsAuth,
      }

} );


export default useAuthModel;