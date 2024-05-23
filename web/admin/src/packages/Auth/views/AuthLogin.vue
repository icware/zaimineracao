<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, onMounted, reactive } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';
import { FieldValide } from '@/service/FieldValidate';
import { AuthService } from '@/service/auth/AuthService';
import { useToast } from 'primevue/usetoast';


const { layoutConfig } = useLayout();
const fieldValide = new FieldValide();
const authService = new AuthService();

const router = useRouter();
const auth = useAuthStore();

const toast = useToast();

const showError = (summary, detail) => {
    toast.add({ severity: 'error', summary: summary, detail: detail, life: 3000 });
};

// Datas
const data = reactive({
    email: ref(''),
    password: ref(''),
});

function dataValidate(data) {
    let isValid = true;

    try {
        isValid = fieldValide.field(data.email);
    } catch (error) {
        isValid = false;
        showError('Usuário', error.message);
    }

    try {
        isValid = fieldValide.field(data.password);
    } catch (error) {
        isValid = false;
        showError('Senha', error.message);
    }

    return isValid;

}

async function login() {
    const isValid = dataValidate(data);

    if (!isValid) {
        return;
    }
    try {
        const response = await authService.login(data);
        auth.setData(response.data);
        window.location.href = "/";
    } catch (error) {
        showError('Falha', error.message);
    }

}

onMounted(() => {
    if (auth.getIsAuth) {
        window.location.href = "/";
    }
})

</script>

<template>
    <div
        class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img src="/images/login.jpg" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Zai Administração!</div>
                        <span class="text-600 font-medium">Faça login para continuar</span>
                    </div>

                    <Toast />

                    <form @submit.prevent="login">

                        <div>
                            <label for="email" class="block text-900 text-xl font-medium mb-2">Usuário</label>
                            <div class="col">
                                <InputText id="email" type="text" placeholder="Usuário" class="w-full md:w-30rem mb-5"
                                    style="padding: 1rem" v-model="data.email" />
                            </div>


                            <label for="password" class="block text-900 font-medium text-xl mb-2">Senha</label>

                            <div class="col">
                                <Password id="password" v-model="data.password" placeholder="Senha" :toggleMask="true"
                                    class="w-full mb-3" inputClass="w-full" :inputStyle="{ padding: '1rem' }">
                                </Password>
                            </div>

                            <div class="flex align-items-center justify-content-between mb-5 gap-5">
                                <div class="flex align-items-center">
                                    <a class="font-medium no-underline ml-2 text-right cursor-pointer"
                                        style="color: var(--primary-color)">Criar conta</a>
                                </div>
                                <a class="font-medium no-underline ml-2 text-right cursor-pointer"
                                    style="color: var(--primary-color)">Esqueci a senha?</a>
                            </div>
                            <Button type="submit" label="Entrar" class="w-full p-3 text-xl"></Button>
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
