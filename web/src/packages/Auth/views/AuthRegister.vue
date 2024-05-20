<template>
    <div>
        <h1>Cadastre-se</h1>
        <form @submit.prevent="submitForm">
            <div>
                <label for="first_name">Nome:</label>
                <input v-model="data.first_name" type="text" id="first_name">
            </div>
            <div>
                <label for="last_name">Sobrenome:</label>
                <input v-model="data.last_name" type="text" id="last_name">
            </div>
            <div>
                <label for="email">E-mail:</label>
                <input ref="emailInput" v-model="data.email" type="email" id="email">
                <div v-if="emailError" style="color: red;">
                    {{ emailError }}
                </div>
            </div>
            <div>
                <label for="password">Senha:</label>
                <input ref="passInput" v-model="data.password" type="password" id="password">
                <div v-if="passError" style="color: red;">
                    {{ passError }}
                </div>
            </div>
            <div>
                <label for="passConfirm">Confirmar Senha:</label>
                <input ref="passConfirmInput" v-model="passConfirm" type="password" id="passConfirm">
                <div v-if="passConfirmError" style="color: red;">
                    {{ passConfirmError }}
                </div>
            </div>
            <div>
                <label for="phone">Telefone:</label>
                <input v-model="data.phone" type="text" id="phone">
            </div>
            <div>
                <button type="submit">Cadastrar</button>
                <router-link to="/" class="button">Cancelar</router-link>
            </div>
            <div v-if="MessageError" role="alert">
                {{ MessageError }}
            </div>
        </form>
    </div>
</template>


<script setup>
import { reactive, ref } from 'vue';
import { useRoute } from 'vue-router';


//uses
const router = useRoute();

//erros
const MessageError = ref('');
const emailError = ref('');
const passError = ref('');
const passConfirmError = ref('');

//refs
const emailInput = ref(null);
const passInput = ref(null);
const passConfirmInput = ref(null);


//datas
const passConfirm = ref('');
const data = reactive({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    phone: ''
});

function dataValidate(data) {
    let isValid = true;

    try {
        isValid = validEmail(data.email);
    } catch (error) {
        emailInput.value.focus();
        emailError.value = error.message;
        isValid = false;
    }
    try {
        isValid = validField(data.password);
    } catch (error) {
        passInput.value.focus();
        passError.value = error.message;
        isValid = false;
    }
    try {
        isValid = validField(passConfirm.value);
    } catch (error) {
        passConfirmInput.value.focus();
        passConfirmError.value = error.message;
        isValid = false;
    }
    try {
        isValid = compareField(passConfirm.value, data.password);
    } catch (error) {
        passConfirmInput.value.focus();
        passConfirmError.value = error.message;
        isValid = false;
    }

    return isValid;
}

async function submitForm() {
    const isValid = dataValidate(data);

    if (!isValid) {
        return;
    }

    try {
        const response = await AuthRegister(data);

        if (response.status === 201) {
            MessageError.value = response.data.sucess;
            router.push('/dashboard');
        } else {
            if (response.data) {
                MessageError.value = response.data.error;
            } else {
                MessageError.value = 'Falha ao se cadastrar!';
            }
        }
    } catch (error) {
        MessageError.value = 'Falha ao se cadastrar!';
    }

}

</script>

<style lang="scss" scoped></style>