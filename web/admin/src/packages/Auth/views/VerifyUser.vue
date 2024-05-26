<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, onMounted, reactive } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';
import { FieldValide } from '@/service/FieldValidate';
import { AuthService } from '@/service/auth/AuthService';
import { useToast } from 'primevue/usetoast';

const authStore = useAuthStore();
const controller = new AuthService();
const validate = new FieldValide();

const router = useRouter();
const toast = useToast();

const requestButtonDisabled = ref(false);
const validateButtonDisabled = ref(true);
const valueCode = ref(null);

function disableRequestButton() {
    requestButtonDisabled.value = true;
    setTimeout(() => {
        requestButtonDisabled.value = false;
    }, 120000);
}

function backDashborad() {
    window.location.href = '/login';
}

async function requestCode() {
    try {
        if (authStore.authenticated) {
            const response = await controller.verifyEmail();
            if (response.status === 200) {
                toast.add({ severity: 'success', summary: 'Email verificado com sucesso', detail: 'Atenção', group: 'br', life: 4000 });
                validateButtonDisabled.value = true;
                setTimeout(() => {
                    authStore.Logout();
                }, 6000);
            }
        } else {
            toast.add({ severity: 'error', summary: 'Usuário não esta logado', detail: 'Acesso negado', group: 'br', life: 4000 });
        }

    } catch (error) {
        toast.add({ severity: 'error', summary: error.message, detail: 'Falha na requisição', group: 'br', life: 4000 });
    }
}

async function validateCode() {

    try {
        if (validate.field(valueCode.value)) {
            const response = await controller.validCode(
                { verification_code: valueCode.value }
            );
            if (response.status === 200) {
                toast.add({ severity: 'success', summary: 'Um código foi enviado para o seu e-mail', detail: 'Atenção', group: 'br', life: 4000 });
                validateButtonDisabled.value = false;
                disableRequestButton();
            }
        }

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Sua solicitação noi foi processada', detail: 'Atenção', group: 'br', life: 4000 });
        validateButtonDisabled.value = true;
    }
}

onMounted(() => {
    if (!authStore.authenticated) {
        router.push('/login');
    }
})

</script>
<template>
    <div
        class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <div
                style="border-radius: 56px; padding: 0.1rem; background: linear-gradient(180deg, var(--primary-color) 100%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">

                    <div class="text-center mb-5">
                        <img src="/images/login.jpg" alt="Sakai logo" class="mb-7 w-8rem flex-shrink-0" style="border-radius: 56px; padding: 0.1rem; 
                background: linear-gradient(180deg, var(--primary-200) 100%, rgba(33, 150, 243, 0) 30%);
                " />
                        <div class="text-900 text-3xl font-medium mb-3">Zai Mineração</div>
                        <span v-if="!authStore.verified" class="text-600">Usuário não verificado</span>
                        <span v-else-if="authStore.verified" class="text-600">Usuário verificado</span>
                    </div>

                    <div>
                        <div class="flex align-items-center justify-content-between mb-5 gap-5">

                            <Button v-if="!authStore.verified && !requestButtonDisabled" @click="requestCode"
                                type="submit" label="Solicitar código" severity="warning"
                                class="w-full p-3 md:w-30rem mb-5 text-xl" rounded />
                            <Button v-else-if="authStore.verified" type="submit" label="Dashboard" severity="secondary"
                                @click="backDashborad" class="w-full p-3 md:w-30rem mb-5 text-xl" rounded />

                        </div>

                    </div>

                    <div v-if="requestButtonDisabled" class="w-full p-3 md:w-30rem mb-5 text-xl">
                        <p class="m-0">Um código foi enviado para o e-mail. {{ authSrore.user.email }}
                            Por favor, verifique sua caixa de entrada, incluindo
                            a pasta de spam, antes de solicitar um novo código.</p>
                    </div>

                    <div v-if="!authStore.verified" class="text-center mb-5">
                        <label for="code" class="block text-900 text-xl font-medium mb-2">Já tenho um código</label>
                        <InputText id="code" type="text" class="w-full md:w-30rem mb-5" style="padding: 1rem"
                            v-model="valueCode" />

                        <div class="field mb-5">
                            <Button :disabled="!valueCode" type="submit" label="Validar" @click="validateCode"
                                class="w-full p-3 md:w-30rem mb-5 text-xl" severity="success" rounded />
                        </div>

                    </div>
                    <Toast position="bottom-right" group="br" />
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