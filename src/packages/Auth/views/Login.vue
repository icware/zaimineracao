<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, computed, onMounted } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import useAuthModel from '@/models/Auth';
import { useRouter } from 'vue-router';
import { AuthLogin } from '@/controllers/AuthController';
import { validField, validEmail } from '@/controllers/ValidateController';

const { layoutConfig } = useLayout();
const email = ref('');
const password = ref('');

const router = useRouter();
const auth = useAuthModel();

// Datas
const data = reactive({
    username: ref(''),
    password: ref(''),
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
        passwordError.value = error.message;
        passwordInput.value.focus();
        isValid = false;
    }

    return isValid;

}


const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});
</script>

<template>
    <div
        class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <img src="/demo/images/login/avatar.png" alt="Image" height="50" class="mb-3" />
                        <div class="text-900 text-3xl font-medium mb-3">Bem-vindo!, Zai Mineração!</div>
                        <span class="text-600 font-medium">Faça login para continuar</span>
                    </div>

                    <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <div class="col">
                            <InputText id="email1" type="text" placeholder="Email address"
                                class="w-full md:w-30rem mb-5" style="padding: 1rem" v-model="email" />
                            <InlineMessage>Email não informado</InlineMessage>
                        </div>


                        <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>

                        <div class="col">
                            <Password id="password1" v-model="password" placeholder="Password" :toggleMask="true"
                                class="w-full mb-3" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>
                            <InlineMessage>Email não informado</InlineMessage>
                        </div>

                        <div class="flex align-items-center justify-content-between mb-5 gap-5">
                            <div class="flex align-items-center">
                                <a class="font-medium no-underline ml-2 text-right cursor-pointer"
                                    style="color: var(--primary-color)">Criar conta</a>
                            </div>
                            <a class="font-medium no-underline ml-2 text-right cursor-pointer"
                                style="color: var(--primary-color)">Esqueci a senha?</a>
                        </div>
                        <Button label="Login" class="w-full p-3 text-xl"></Button>
                    </div>
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
