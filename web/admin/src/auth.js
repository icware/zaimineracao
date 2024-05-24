import { useAuthStore } from './store/AuthStore';
import { AuthService } from '@/service/auth/AuthService';

export default function checkAuth() {
    if (localStorage.getItem('token')) {
        (async () => {
            const auth = useAuthStore();
            const authService = new AuthService();
    
            try {            
                auth.setIsAuth(true);
                await authService.check();
            } catch (error) {
                auth.setIsAuth(false);
                auth.authLogout();
                console.log(error.message);
            }
    
        } ) ()
    }
    
}