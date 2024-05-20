import { ApiError, apiRequest, apiUrls } from "../api/ApiService";

export class AuthService {
    constructor() {
        this.url = new apiUrls();
        this.api = new apiRequest();
    }

    async login(data){
        try {
            const url = this.url.auth();
            return await this.api.execute('post', url, data);
        } catch (error) {
            ApiError.handleError(error);
        }
    }

    async register(data) {
        try {
            const url = this.url.auth('register');
            return await this.api.execute('post', url, data);
        } catch (error) {
            ApiError.handleError(error);
        }
    }

    async check() {
        try {
            const url = this.url.auth('check/');
            return await this.api.execute('get', url);
        } catch (error) {
            ApiError.handleError(error);
        }
    }

}