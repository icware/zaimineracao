import axios from "axios";
import AppSettings from "@/service/config";
import useAuthModel from "@/models/Auth";


const serverRequests = axios.create({
    baseURL: AppSettings.server.address,
    headers: AppSettings.headers
});

const ApiRequests = axios.create({
    headers: AppSettings.headers
});

serverRequests.interceptors.request.use(
    requestConfig => {
        const auth = useAuthModel();
        const authToken = auth.getToken;

        if(authToken){
         requestConfig.headers["Authorization"] = `Bearer ${authToken}`;
        }
        return requestConfig;
    },
    error => {
        return Promise.reject(error);
    }
);

ApiRequests.interceptors.request.use(
    requestConfig => {
        const auth = useAuthModel();
        if (!auth.getIsAuth){
            return;
           }           
           const authToken = auth.getToken;

           if(authToken){
            requestConfig.headers["Authorization"] = `Bearer ${authToken}`;
           }

        return requestConfig;
    },
    error => {
        return Promise.reject(error);
    }
);

export async function ServerRequest(method, url, data = null) {
    try {
  
      const response = await serverRequests({
          method: method,
          url:url,
          data:data
      });
  
      return response;
      
    } catch (error) {
      if(error.response) {
          throw new Error(error.response.data.error);
      } else if (error.request) {
          throw new Error('Sem resposta do servidor.');
      } else {
          throw new Error(error.message);
      }
    }
  }


export async function ApiRequest(method, url, data = null) {
  try {
    const response = await ApiRequests({
        method: method,
        url:url,
        data:data
    });

    return response;
    
  } catch (error) {
    if(error.response) {
        throw new Error(error.response.data.error);
    } else if (error.request) {
        throw new Error('Sem resposta da API.');
    } else {
        throw new Error(error.message);
    }
  }
}
