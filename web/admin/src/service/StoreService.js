import { EncryptService } from "./EncryptService";


export class StoreService {
    constructor(encrypt = new EncryptService()) {
        this.encrypt = encrypt;
    }

    
    setValue(key, value) {
        try {
            if (key && value) {
                const encrypted = this.encrypt.set(value);
                this.set(key, encrypted);                
            }
        } catch (error) {
            throw new Error('Falha ao tentar salvar o item')
        }
    }

    getValue(key){
        try {
            const sotre = this.get(key);
            if (sotre) {
                return this.encrypt.get(sotre);
            }
            
        } catch (error) {
            throw new Error(`Falha ao tentar recuperar item: ${error.message}`);
        }
            
    }
    
    set(key, value) {        
        localStorage.setItem(key, value);
    }

    get(key) {
        const store = localStorage.getItem(key);
        return store ? store : null; 
    }

    del(key=null){
        if(key){
            localStorage.removeItem(key)
        } else {
            localStorage.clear();
        }
    }

    getJson(key){
        try {
            const value = this.getValue(key);
            if (value) {
                const serializer = JSON.parse(value);
                return serializer;
            }           

        } catch (error) {
            throw new Error(error.message);
        }

    }

    setJson(key, value) {
        try {
           const serializer = JSON.stringify(value);
           this.setValue(key, serializer);
        } catch (error) {
            throw new Error(error.message);
        }
    }
}