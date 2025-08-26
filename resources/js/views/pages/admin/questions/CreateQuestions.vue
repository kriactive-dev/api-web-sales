<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router';
import { useForm } from 'vee-validate';
import * as yup from 'yup';

const router = useRouter();
const toast = useToast();
const isLoadingButton = ref(false);

const typeOptions = [
    { label: 'Texto', value: 'text' },
    { label: 'Botão', value: 'button' },
];

const schema = yup.object({
    text: yup.string().required().trim().label('Texto da Pergunta'),
    type: yup.string().required().label('Tipo'),
    is_start: yup.boolean().label('Pergunta Inicial'),
    active: yup.boolean().label('Ativa')
});

const { defineField, handleSubmit, errors, setErrors } = useForm({
    validationSchema: schema
});

const [text] = defineField('text');
const [type] = defineField('type');
const [is_start] = defineField('is_start');
const [active] = defineField('active');

const onSubmit = handleSubmit((values) => {
    isLoadingButton.value = true;
    axios
        .post(`/api/questions`, values)
        .then(() => {
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Pergunta criada com sucesso', life: 3000 });
            router.back();
        })
        .catch((error) => {
            isLoadingButton.value = false;
            toast.add({ severity: 'error', summary: 'Erro', detail: error.response?.data?.message || 'Erro ao criar', life: 3000 });
            if (error.response?.data?.errors) {
                setErrors(error.response.data.errors);
            }
        })
        .finally(() => {
            isLoadingButton.value = false;
        });
});
</script>

<template>
    <div class="card flex flex-col gap-4">
        <div class="w-full">
                    <Button label="Voltar" class="mr-2 mb-2" @click="router.back()"><i class="pi pi-angle-left"></i> Voltar</Button>
                </div>
        <div class="font-semibold text-xl">Adicionar Pergunta</div>
        <small class="p-error">Os campos marcados * são obrigatórios.</small>
        <form @submit="onSubmit">
            <div class="flex flex-col gap-2">
                <label for="text">Texto da Pergunta *</label>
                <InputText v-model="text" id="text" placeholder="Digite a pergunta" :class="{ 'p-invalid': errors.text }" />
                <small class="p-error">{{ errors.text }}</small>
            </div>
            <div class="flex flex-col gap-2">
                <label for="type">Tipo *</label>
                <Dropdown v-model="type" id="type" :options="typeOptions" optionLabel="label" optionValue="value" placeholder="Selecione o tipo" :class="{ 'p-invalid': errors.type }" />
                <small class="p-error">{{ errors.type }}</small>
            </div>
            <div class="flex items-center gap-2">
    <Checkbox v-model="is_start" :binary="true" inputId="is_start" />
    <label for="is_start">Esta é a pergunta inicial?</label>
</div>
<div class="flex items-center gap-2">
    <Checkbox v-model="active" :binary="true" inputId="active" />
    <label for="active">Pergunta ativa?</label>
</div>
            <Button label="Submeter" class="mt-2" :disabled="isLoadingButton" @click="onSubmit" />
            <ProgressSpinner v-if="isLoadingButton" style="width:35px;height:35px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" aria-label="Salvando..." />
        </form>
    </div>
</template>