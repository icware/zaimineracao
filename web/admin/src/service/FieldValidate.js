
export class FieldValide {
    

    field ( value ) {
        if (!value) {
            throw new Error ('Campo obrigatório');
        }
        return true;
    }

    compare(value, another) {
        try {
            if (this.field(value)) {
                if (value !== another) {
                    throw new Error('Os campos não confere');
                }
                return true;
            }
        } catch (error) {
            throw new Error(error.message);
        }
        
    }

    email (value) {
        try {
            if (this.field(value)) {
               const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
               if (!emailRegex.test(value)) {
                    throw new Error('Email inválido');
               }
            }
        } catch (error) {
            throw new Error(error.message);
        }
    }

    password(value) {
        try {
           if ( this.field(value)) {
                if (!value || typeof value !== 'string') {
                    throw new Error('A senha fornecida é inválida.');
                }

                const hasUpperCase = /[A-Z]/.test(value);
                const hasLowerCase = /[a-z]/.test(value);
                const hasNumber = /[0-9]/.test(value);
                const hasSpecialChar = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/.test(value);
                const isLongEnough = value.length >= 8;
        
                if (!(hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar && isLongEnough)) {
                    throw new Error('A senha não atende aos critérios mínimos de segurança.');
                }

                return true;
           }
        } catch (error) {
            throw new Error(error.message);
        }

    }

    cpf(value) {
        const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;

        if (!cpfRegex.test(value)) {
            throw new Error('CPF inválido');
        }        
    }

    cep(value) {
        const cepRegex = /^\d{5}-\d{3}$/;
        if (!cepRegex.test(value)) {
            throw new Error('CEP inválido');
        }
    }

    cnpj(value) {
        const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
        if (!cnpjRegex.test(value)) {
            throw new Error('CNPJ inválido');
        }
    }

}


