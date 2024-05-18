import CryptoJS from 'crypto-js';

const _secret = 'qt60O30OK07gNdmdyQoYG2EfLL2K8vjEkcBML4nooN7C06cshx4W1rKKfKwX1yb0'

export function crypt(value) {
    try {
        if (value) {
            const encrypted = CryptoJS.AES.encrypt(value, _secret).toString();
            return encrypted;
        }
    } catch (erro) {
        console.log('Erro ao tentar encriptar');
        console.log(erro);        
    }
}

export function uncrypt(value){
    try {
        if (value) {
            const bytes = CryptoJS.AES.decrypt(value, _secret)
            const decrypted = bytes.toString(CryptoJS.enc.Utf8);  
            return decrypted;
        }
      
    } catch (error) {
        console.log('Erro ao tentar decriptar');
        console.log(error);         
    }
}