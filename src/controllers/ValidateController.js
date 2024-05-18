

export function validField (value) {
    if (!value) {
        throw new Error('Campo obrigatório!');
    } else {
        return true;
    }
}

export function compareField(first, last) {
    if (!first || !last) {
        throw new Error('Os campos não podem estar vazios.');
    }

    if (first !== last) {
        throw new Error('Os campos não conferem.');
    }
    
    return true;
}

export function validEmail(value) {
    if (!value) {
        throw new Error('Campo obrigatório!');
    }
    return true
}

export function validatePassword(password) {
    if (!password || typeof password !== 'string') {
        throw new Error('A senha fornecida é inválida.');
    }

    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecialChar = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/.test(password);
    const isLongEnough = password.length >= 8;

    if (!(hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar && isLongEnough)) {
        throw new Error('A senha não atende aos critérios mínimos de segurança.');
    }

    return true;
}