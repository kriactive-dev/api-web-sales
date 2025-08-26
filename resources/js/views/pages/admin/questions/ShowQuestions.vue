<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { debounce } from 'lodash-es';
import moment from 'moment';

const router = useRouter();
const toast = useToast();
const isLoadingDiv = ref(true);
const displayConfirmation = ref(false);
const retriviedData = ref({});
const questionsList = ref([]); // Lista de perguntas para o Dropdown
const dataIdBeingDeleted = ref(null);
const showAddOptionDialog = ref(false);
const newOption = ref({
  label: '',
  value: '',
  next_question_bot_id: ''
});

function submitOption() {
  const payload = {
    ...newOption.value,
    question_bot_id: retriviedData.value.id
  };
  axios.post('/api/options', payload)
    .then(() => {
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Opção adicionada!', life: 3000 });
      showAddOptionDialog.value = false;
      getData(); // Recarrega as opções
      newOption.value = { label: '', value: '', next_question_bot_id: '' };
    });
}

function goBackUsingBack() {
    router.back();
}

const closeConfirmation = () => {
    displayConfirmation.value = false;
};
const confirmDeletion = (id) => {
    displayConfirmation.value = true;
    dataIdBeingDeleted.value = id;
};

const getData = async () => {
    isLoadingDiv.value = true;
    axios
        .get(`/api/questions/${router.currentRoute.value.params.id}`)
        .then((response) => {
            retriviedData.value = response.data;
            isLoadingDiv.value = false;
        })
        .catch((error) => {
            isLoadingDiv.value = false;
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Não foi possível carregar a pergunta', life: 3000 });
            goBackUsingBack();
        });
    axios
        .get('/api/questions')
        .then((response) => {
            questionsList.value = response.data.data;
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Não foi possível carregar as perguntas', life: 3000 });
        });
};

const deleteData = () => {
    axios
        .delete(`/api/questions/${dataIdBeingDeleted.value}`)
        .then(() => {
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Pergunta apagada com sucesso', life: 3000 });
            closeConfirmation();
            goBackUsingBack();
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Erro ao apagar', life: 3000 });
        });
};

onMounted(() => {
    getData();
});
</script>

<template>
    <div class="flex flex-col min-h-screen items-center justify-center" v-if="isLoadingDiv">
        <div class="flex flex-col gap-4 text-center">
            <ProgressSpinner style="width: 50px; height: 50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" aria-label="Carregando" />
            <p>Por Favor Aguarde...</p>
        </div>
    </div>
    
    <div class="flex flex-col gap-12" v-else>
        <div class="card flex flex-col gap-4">
            <div class="flex justify-start">
                <Button label="Voltar" class="mr-2 mb-2" @click="goBackUsingBack">
                    <i class="pi pi-angle-left"></i> Voltar
                </Button>
            </div>
            <div class="font-semibold text-xl">Detalhes da Pergunta</div>
            <div><strong>Pergunta:</strong> {{ retriviedData.text }}</div>
            <div><strong>Tipo:</strong> {{ retriviedData.type === 'button' ? 'Botão' : 'Texto' }}</div>
            <div><strong>Data de Criação:</strong> {{ moment(retriviedData.created_at).format('DD-MM-YYYY HH:mm') }}</div>
            <!-- Listando as opções -->
    <div v-if="retriviedData.options && retriviedData.options.length">
      <div class="font-semibold mt-4 mb-2">Opções</div>
      <DataTable :value="retriviedData.options">
        <Column field="label" header="Texto do Botão" />
        <Column field="value" header="Valor" />
        <Column field="next_question.text" header="Próxima Pergunta" />
        <Column header="Ações">
          <template #body="slotProps">
            <Button icon="pi pi-pencil" @click="editOption(slotProps.data)" class="m-2" />
            <Button icon="pi pi-trash" @click="deleteOption(slotProps.data.id)" class="m-2" />
          </template>
        </Column>
      </DataTable>
    </div>
    <!-- Formulário para adicionar uma nova opção -->
    <div class="mt-4">
      <Button label="Adicionar opção" @click="showAddOptionDialog = true" />
<Dialog 
  header="Nova Opção" 
  v-model:visible="showAddOptionDialog" 
  :modal="true"
  :style="{ width: '400px', borderRadius: '12px' }"
>
  <form @submit.prevent="submitOption" class="flex flex-col gap-4 p-2">
    <div>
      <label for="label" class="font-semibold mb-1 block">Texto do Botão</label>
      <InputText v-model="newOption.label" id="label" class="w-full" />
    </div>
    <div>
      <label for="value" class="font-semibold mb-1 block">Valor</label>
      <InputText v-model="newOption.value" id="value" class="w-full" />
    </div>
    <div>
  <label for="next_question_bot_id" class="font-semibold mb-1 block">Próxima Pergunta</label>
    <Dropdown
        v-model="newOption.next_question_bot_id"
        id="next_question_bot_id"
        :options="questionsList"
        optionLabel="text"
        optionValue="id"
        filter
        placeholder="Selecione a próxima pergunta"
        class="w-full" 
        showClear
    />
    </div>
    <!-- <div>
      <label for="next_question_bot_id" class="font-semibold mb-1 block">Próxima Pergunta (ID)</label>
      <InputText v-model="newOption.next_question_bot_id" id="next_question_bot_id" class="w-full" />
    </div> -->
    <div class="flex justify-end gap-2 mt-2">
      <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="showAddOptionDialog = false" />
      <Button label="Salvar" type="submit" icon="pi pi-check" severity="success" />
    </div>
  </form>
</Dialog>
    </div>
            <div>
                <Button label="Apagar" icon="pi pi-trash" severity="danger" @click="confirmDeletion(retriviedData.id)" />
            </div>
        </div>
    </div>
    <Dialog header="Confirmação" v-model:visible="displayConfirmation" :style="{ width: '350px' }" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span>Tem certeza que deseja apagar esta pergunta?</span>
        </div>
        <template #footer>
            <Button label="Não" icon="pi pi-times" @click="closeConfirmation" class="p-button-text" />
            <Button label="Sim" icon="pi pi-check" @click="deleteData" class="p-button-text" autofocus />
        </template>
    </Dialog>
</template>