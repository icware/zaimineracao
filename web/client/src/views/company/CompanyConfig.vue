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

                <div class="field col-12 md:col-4">
                    <label for="directory">Diretório</label>
                    <InputText id="directory" v-model="data.directory" disabled />
                </div>

                <div class="field col-12 md:col-4">
                    <FileUpload mode="basic" name="demo[]" url="/api/upload" accept="image/*" :maxFileSize="1000000"
                        @upload="onUpload" :auto="true" chooseLabel="Adicionar logo" />
                </div>

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
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';

const data = ref({
    link: '',
    email: '',
    directory: '',
});

const toast = useToast();

const onUpload = () => {
    toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
};

const show = () => {
    toast.add({ severity: 'info', summary: 'Info', detail: 'Mensagem de informação!', life: 3000 });
};
</script>
