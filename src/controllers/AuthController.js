import {ServerRequest} from "@/service/Requests";
import AppSettings from "@/service/config";
import useAuthModel from "@/models/Auth";

export async function AuthLogin(email, password) {
    try {
        if (!email || !password) {
            throw new Error('Email e senha são obrigatórios.');
        }

        const dataRequest = {
            email: btoa(email),
            password: btoa(password)
        };

        const response = await ServerRequest('post', AppSettings.server.authToken, dataRequest);
        return response.data;
    } catch (error) {
        if (error.response) {
            throw new Error(error.response.data.error);
        } else if (error.request) {  
            throw new Error('Não foi possível obter resposta do servidor.');
        } else {
            throw new Error(error.message);
        }
    }
}

export async function AuthRegister ( dataRequest ) {
   
    if (!dataRequest) {
        return null;
    }
    try {
        const response = await ServerRequest('post', AppSettings.server.authRegister, dataRequest);

        if (response) {
            return response
        }

    } catch (error) {
        return null;
    }

}

export async function AuthCheck () {
    const auth = useAuthModel()
    try {
      const response = await ServerRequest('get', AppSettings.server.authToken);
      console.log(response);
        auth.setUser(response.data);
      return true;
    } catch (error) {        
        return false;
    }

}