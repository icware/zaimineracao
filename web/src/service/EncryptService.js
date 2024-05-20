import CryptoJS from 'crypto-js';
import { secretKey } from '../config';


export class EncryptService {
    constructor(secret= secretKey) {
        this.secret = secret
    }

    set(value) {
        try {
            if (value) {
                const encrypted = CryptoJS.AES.encrypt(value, this.secret).toString();
                return encrypted;
            }
            
        } catch (error) {
            throw new Error(`ERRO: | Falha ao tentar codificar o valor - ${error.message}`);
        }
    }

    get(value) {
        try {
            if (value) {
                const bytes = CryptoJS.AES.decrypt(value, this.secret);
                const decrypted = bytes.toString(CryptoJS.enc.Utf8);
                return decrypted;
            }         
            
       } catch (error) {
            throw new Error(`ERRO: | Falha ao tentar decodificar o valor - ${error.message}`);
        }
    }

}

