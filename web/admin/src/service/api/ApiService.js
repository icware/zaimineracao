import axios from "axios";
import { apiAddrres } from "@/config";
import { useAuthStore } from '@/store/AuthStore';


const defaultHeaders = {
    Accept: 'application/json',
    'Content-Type': 'application/json'
};

export class apiUrls {
    
    constructor( address=apiAddrres.address, point=apiAddrres.base, version=apiAddrres.version ) {
        this.address = address;
        this.point = point;
        this.version = version;
    }

    api(urls) {
        let baseUrl = null;

        if(this.point && this.version) {
            baseUrl = `${this.address}/${this.point}/${this.version}/${urls}`;
        } else if(this.point && !this.version) {
            baseUrl = `${this.address}/${this.point}/${urls}`;
        } else {
            baseUrl = `${this.address}/${urls}`
        }
       
        return baseUrl;
    }


    company(company) {
        if (company) {
            return this.api(`company/${company}/`);
        } else {
            return this.api(`company/`);
        }
    }

    companyData(url) {
        if (url) {
            return this.api(`company/data/${url}`);
        } else {
            return this.api(`company/data/`);
        }
    }

    associate(company, associate=null) {

        if (associate) {
            return this.api(`company/${company}/associate/${associate}`);
        } else {
            return this.api(`company/${company}/associate/`);
        }
    }
  
}


export class ApiError extends Error {
    constructor(error) {
        super(error.message);
        this.response = error.response;
        this.request = error.request;
    }

    static handleError(error) {
        if (error.response) {
            throw new ApiError(new Error(error.response.data.error));
        } else if (error.request) {
            throw new ApiError(new Error('Servidor sem resposta'));
        } else {
            throw new ApiError(new Error(error.message));
        }
    }
}


export class apiRequest{
    constructor() {  
        this.axios = axios.create({
            headers:defaultHeaders
        });

        this.axios.interceptors.request.use(
            requestConfig => {
                const auth = useAuthStore()            
                const token = auth.getToken
                if (token) {
                    requestConfig.headers["Authorization"] = `token ${token}`;
                }
                return requestConfig;
            },
            error => {
                return Promise.reject(error);
            }
        );
   }

    async execute(method, url, data=null){
    try {
        return await this.axios({
            method:method,
            url:url,
            data:data
        })
    } catch (error) {
        ApiError.handleError(error);
    }
   }

}

