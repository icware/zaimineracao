import { ApiError, apiRequest, apiUrls } from "../../service/api/ApiService";

export class ServerController {

    constructor() {
        this.api = new apiRequest();
        this.buildUrl = new apiUrls();
        this.url = this.buildUrl.api('servers')
    }
    async get(id = null) {
        try {
            if (!id) {
                return await this.api.execute('get', `${this.url}`);

            } else {
                return await this.api.execute('get', `${this.url}/${id}`);
            }

        } catch (error) {
            ApiError.handleError(error);
        }
    }
    async set(data, id = null) {
        try {
            if (!id) {
                return await this.api.execute('post', `${this.url}`, data);
            } else {
                return await this.api.execute('put', `${this.url}/${id}`);
            }

        } catch (error) {
            ApiError.handleError(error);
        }

    }
    async del(id) {
        try {
            return await this.api.execute('delete', `${this.url}/${id}`);
        } catch (error) {
            ApiError.handleError(error);
        }
    }
}