import { ApiError, apiRequest, apiUrls } from "../api/ApiService";
import { useAuthStore } from '@/store/AuthStore';


export class AuthService {
  constructor() {
    this.url = new apiUrls();
    this.api = new apiRequest();
  }

  async login(data) {
    try {
      const url = this.url.api('auth/login');
      return await this.api.execute("post", url, data);
    } catch (error) {
      ApiError.handleError(error);
    }
  }

  async register(data) {
    try {
      const url = this.url.auth("auth");
      return await this.api.execute("post", url, data);
    } catch (error) {
      ApiError.handleError(error);
    }
  }

  async check() {
    try {
      const url = this.url.api("auth/admin/token");
      return await this.api.execute("get", url);
    } catch (error) {
      ApiError.handleError(error);
    }
  }

  async verifyEmail() {
    try {
      const url = this.url.api("email/verify");
      return await this.api.execute("post", url);
    } catch (error) {
      ApiError.handleError(error);
    }
  }

  async validCode(data) {
    try {
      const url = this.url.api("email/verify/code");
      return await this.api.execute("post", url, data);
    } catch (error) {
      ApiError.handleError(error);
    }
  }

  async setTheme(data) {
    const authStore = useAuthStore();
    if (authStore.authenticated) {
      try {
        const url = this.url.api("auth/manage/theme");
        const response = await this.api.execute("put", url, data);
        authStore.setTheme(response.data);
        return response;
      } catch (error) {
        ApiError.handleError(error);
      }
    }
  }
}
