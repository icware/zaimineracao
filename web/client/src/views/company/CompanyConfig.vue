<template>
    <Card>
        <template #title>Teste 1</template>
        <template #subtitle>Configuração</template>
        <template #content>
            <div class="p-fluid grid">
                <div class="field col-12 md:col-4">
                    <label for="link">Link</label>
                    <InputText id="link" v-model="data.link" />
                </div>

                <div class="field col-12 md:col-4">
                    <label for="email">Email</label>
                    <InputText id="email" v-model="data.email" />
                </div>

                <!-- Input com botão -->
                <div class="field col-12 md:col-4">
                    <label for="directory">Foto de perfil</label>
                    <div class="p-inputgroup">
                        <Button
                            label="Selecionar"
                            icon="pi pi-plus"
                            class="p-button-info"
                            @click="openFilePicker"
                        />
                        <input
                            type="file"
                            id="fileInput"
                            style="display: none"
                            @change="transferirValor"
                        />
                        <InputText
                            id="directory"
                            v-model="data.directory"
                            readonly
                            placeholder="Diretório"
                        />
                    </div>
                </div>
                <!-- ----------------- -->
            </div>

            <div class="flex flex-wrap gap-2 justify-content-center mt-4">
                <Button label="Salvar" @click="show()" />
                <Button label="Cancelar" class="p-button-secondary" />
            </div>
        </template>
    </Card>
    <Toast />
</template>

<script setup>
    import { useToast } from 'primevue/usetoast';
    import Toast from 'primevue/toast';
    import { reactive } from 'vue';

    const data = reactive({
        link: '',
        email: '',
        directory: '',
    });

    const toast = useToast();

    const openFilePicker = () => {
        const fileInput = document.getElementById('fileInput');
        fileInput.click();
    };

    function transferirValor() {
        try {
            const vOrigem = document.getElementById('fileInput').value;
            document.getElementById('directory').value = vOrigem;
            toast.add({
                severity: 'success',
                summary: 'Sucesso',
                detail: 'Arquivo carregado com sucesso!',
                life: 3000,
            });
        } catch {
            toast.add({
                severity: 'error',
                summary: 'Erro',
                detail: 'Erro ao carregar arquivo!',
                life: 3000,
            });
        }
    }

    const show = () => {
        toast.add({
            severity: 'info',
            summary: 'Info',
            detail: 'Mensagem de Teste',
            life: 3000,
        });
    };
</script>
