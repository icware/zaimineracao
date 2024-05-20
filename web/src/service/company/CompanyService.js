import { ApiError, apiRequest, apiUrls } from "../api/ApiService";

export class CompanyService {
    
    constructor (company = null ){
        this.urls = new apiUrls();
        this.company = company;
        this.api = new apiRequest();
    }
    async getCompanyData(company, filter=null) {
        try {
            let urlFilter = `?code=${company}`;

            if ( filter) {
                urlFilter = `?code=${company}&${filter}`;
            } else {
                urlFilter = `?code=${company}`;
            }
            const url = this.urls.companyData(urlFilter);
            return await this.api.execute('get', url);    
        } catch (error) {
          ApiError.handleError(error);
        }
    }

    async getCompany() {
        try {
            const url = this.company ? this.urls.company(this.company) : this.urls.company();
            return await this.api.execute('get', url);    
        } catch (error) {
          ApiError.handleError(error);
        }
    }


    async setCompany(data){
        try {
            const method = this.company ? 'put' : 'post';
            const url = this.urls.company(this.company || '');
            return await this.api.execute(method, url, data);
        } catch (error) {
            ApiError.handleError(error);
        }
    }
    async delCompany(){
        try {
            const url = this.urls.company(this.company);
            return await this.api.execute('delete', url);
        } catch (error) {
            ApiError.handleError(error);
        }
    }

    async getAssociates(associate = null){
        try {
            const url = associate ? this.urls.associate(this.company, associate) : this.urls.associate(this.company);
            return await this.api.execute(get, url )
        } catch (error) {
            
        }
    }
    async setAssociate(data, associate = null){
        const method = associate ? 'put' : 'post';
        const url = this.urls.associate(this.company, associate || '' );
        return await this.api.execute(method, url, data)
    }
    async delAssociate(associate) {
        const method = 'delete';
        const url = this.urls.associate(this.company, associate);
        return await this.this.api.execute(method, url);
    }
}