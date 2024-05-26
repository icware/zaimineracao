import { defineStore } from 'pinia';
import { computed, reactive, ref } from 'vue';
import { StoreService } from '../service/StoreService';
import { AuthService } from '@/service/auth/AuthService';
import { useLayout } from '@/layout/composables/layout';

const useStoreService = new StoreService();
const { layoutConfig, setScale } = useLayout();

export const useAuthStore = defineStore('auth', () => {
  const Token = ref(useStoreService.getValue('token'));
  const Data = useStoreService.getJson('auth');

  const User = reactive({
    first_name: Data && Data.user ? Data.user.first_name || null : null,
    last_name: Data && Data.user ? Data.user.last_name || null : null,
    email: Data && Data.user ? Data.user.email || null : null,
    code: Data && Data.user ? Data.user.code || null : null,
    phone: Data && Data.user ? Data.user.phone || null : null,
    birth: Data && Data.user ? Data.user.birth || null : null,
    active: Data && Data.user ? Data.user.active || false : false,
    company: Data && Data.user ? Data.user.company || false : false,
    super: Data && Data.user ? Data.user.super || false : false,
    email_verified_at: Data && Data.user ? Data.user.email_verified_at || null : null,
    update_password_at: Data && Data.user ? Data.user.update_password_at || null : null,
  });

  const Theme = reactive({
    theme: Data && Data.theme ? Data.theme.theme || 'aura-dark-blue' : 'aura-dark-blue',
    inputStyle: Data && Data.theme ? Data.theme.inputStyle || 'outlined' : 'outlined',
    scale: Data && Data.theme ? Data.theme.scale || 12 : 12,
    darkTheme: Data && Data.theme ? Data.theme.darkTheme || false : false,
    ripple: Data && Data.theme ? Data.theme.ripple || false : false,
    menuMode: Data && Data.theme ? Data.theme.menuMode || 'static' : 'static',
    activeMenuItem: Data && Data.theme ? Data.theme.activeMenuItem || null : null,
  });

  const isAuth = ref(false);
  const isVerify = ref(User.email_verified_at ? true || false : false);

  function setToken(value) {
    useStoreService.setValue('token', value ? value || null : null);
    if (value) {
      setIsAuth(true);
    }
  }

  function setAuth(value) {
    useStoreService.setJson('auth', value ? value || null : null);
    isVerify.value = User.email_verified_at ? true || false : false
  }

  function validate() {
    if (useStoreService.getValue('token')) {
      (async () => {
        const authService = new AuthService();
        try {
          setIsAuth(true);
          isVerify.value = User.email_verified_at ? true || false : false
          const response = await authService.check();
          setAuth(response.data);
        } catch (error) {
          setIsAuth(false);
          Logout();
        }
      })()
    }
  }

  function setTheme(value) {
    console.log(value);
    Theme.theme = value.theme;
    Theme.scale = value.scale;
    applayTheme();
  }

  function applayTheme() {
    layoutConfig.value = Theme;
    const themeLink = document.getElementById('theme-css');
    if (themeLink) {
      themeLink.href = `/themes/${Theme.theme}/theme.css`;
    }
    document.documentElement.style.fontSize = `${Theme.scale}px`;
  }

  function setIsAuth(value) {
    isAuth.value = value;
  }

  function Logout() {
    useStoreService.del('token');
    useStoreService.del('auth');
    Token.value = null;
    User.value = null;
    isAuth.value = false;
    window.location.href = '/login';
  }

  const authenticated = computed(() => {
    return isAuth.value;
  });

  const theme = computed(() => {
    return Theme.value;
  });

  const token = computed(() => {
    return Token.value;
  });

  const verified = computed(() => {
    return isVerify.value
  });

  return {
    authenticated,
    verified,
    theme,
    token,
    setToken,
    setIsAuth,
    setAuth,
    setTheme,
    Logout,
    applayTheme,
    validate,
  }

});
