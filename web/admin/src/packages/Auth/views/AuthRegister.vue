<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, onMounted, reactive } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';
import { FieldValide } from '@/service/FieldValidate';
import { AuthService } from '@/service/auth/AuthService';


const { layoutConfig } = useLayout();
const fieldValide = new FieldValide();
const authService = new AuthService();

const router = useRouter();
const auth = useAuthStore();


// Datas
const data = reactive({
    first_name: ref(''),
    last_name: ref(''),
    email: ref(''),
    password: ref(''),
    confirmPassword: ref(''),
});

const MessageValid = ref('');
const MenssageCad = ref('');

function dataValidate(data) {
    let isValid = true;

    try {
        isValid = isValid && fieldValide.field(data.first_name);
    } catch (error) {
        isValid = false;
    }

    try {
        isValid = isValid && fieldValide.field(data.last_name);
    } catch (error) {
        isValid = false;
    }

    try {
        isValid = isValid && fieldValide.field(data.email);
    } catch (error) {
        isValid = false;
    }

    try {
        isValid = isValid && fieldValide.field(data.password);
    } catch (error) {
        isValid = false;
    }

    try {
        isValid = isValid && fieldValide.field(data.confirmPassword);
    } catch (error) {
        isValid = false;
    }

    // Check if passwords match
    if (data.password !== data.confirmPassword) {
        isValid = false;
        MessageValid.value = "As senhas não são iguais!";
    } else {
        MessageValid.value = "";
    }

    return isValid;
}

async function register() {
    const isValid = dataValidate(data);

    if (!isValid) {
        return;
    }
    console.log(data);
    try {
        await authService.register(data);
        window.location.href = "/";
        console.log('Apos redirecionar');
    } catch (error) {
        console.log(error);
        MenssageCad.value = error.message;
    }

}


onMounted(() => {
    // if (auth.getIsAuth) {
    //     window.location.href = "/";
    // }
})

</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center overflow-hidden p-4  ">
        <div class="flex flex-column align-items-center justify-content-center">

            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <img src="/images/login.jpg" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
                        <div class="text-900 text-4xl font-medium mb-0">Bem-vindo a Zai mineração!</div>
                        <span class="text-600 font-medium">Faça seu cadastro</span>
                    </div>


                    <form @submit.prevent="register">
                        <div class="flex flex-column gap-4">

                            <div class="flex flex-row gap-4">
                                <div class="flex flex-column">
                                    <label for="first_name" class="block text-900 font-medium text-xl mb-2">Nome</label>
                                    <InputText v-model="data.first_name" id="first_name" type="text"
                                        class="w-full md:w-15rem mb-3" style="padding: 0.75rem;" />
                                </div>
                                <div class="flex flex-column">
                                    <label for="last_name"
                                        class="block text-900 font-medium text-xl mb-2">Sobrenome</label>
                                    <InputText v-model="data.last_name" id="last_name" type="text"
                                        class="w-full md:w-15rem mb-3" style="padding: 0.75rem;" />
                                </div>
                            </div>

                            <div class="flex flex-column">
                                <label for="email" class="block text-900 font-medium text-xl mb-2">Email</label>
                                <InputText v-model="data.email" id="email" type="text" class="w-full md:w-40rem mb-3"
                                    style="padding: 0.75rem;" />
                            </div>

                            <div class="flex flex-column">
                                <label for="password" class="block text-900 font-medium text-xl mb-2">Senha</label>
                                <Password v-model="data.password" id="password" placeholder="Senha" :toggleMask="true"
                                    class="w-full mb-3" inputClass="w-full" :inputStyle="{ padding: '0.75rem' }">
                                </Password>
                            </div>

                            <div class="flex flex-column">
                                <label for="password" class="block text-900 font-medium text-xl mb-2">Confirmar
                                    Senha</label>
                                <Password v-model="data.confirmPassword" id="password" placeholder="Confirmar Senha"
                                    :toggleMask="true" class="w-full mb-3" inputClass="w-full"
                                    :inputStyle="{ padding: '0.75rem' }">
                                </Password>
                                <p v-if="MessageValid" style="color: red;">{{ MessageValid }}</p>
                                <p v-else-if="MenssageCad" style="color: red;">{{ MenssageCad }}</p>
                            </div>

                            <Button type="submit" label="Cadastrar" class="w-full p-2 text-xl"></Button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <AppConfig simple />
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
