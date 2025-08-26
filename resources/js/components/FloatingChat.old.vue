<template>
  <div class="fixed bottom-4 right-4 z-50">
    <!-- Botão flutuante -->
    <button
      @click="toggleChat"
      class="w-14 h-14 rounded-full bg-green-700 text-white shadow-lg flex items-center justify-center text-xl"
    >
      💬
    </button>

    <!-- Janela do chat -->
    <div
      v-if="chatOpen"
      class="w-[400px] h-[600px] bg-white rounded-xl shadow-xl flex flex-col overflow-hidden mt-4"
>
      <div class="bg-green-700 text-white px-4 py-2 text-sm font-bold flex justify-between items-center">
        <span>Assistente Virtual UCM</span>
        <button @click="toggleChat" class="text-white">✖</button>
      </div>

      <div class="flex-1 p-3 overflow-y-auto text-sm space-y-2" ref="messageBox">
        <div v-for="(msg, index) in messages" :key="index" :class="msg.from === 'user' ? 'text-right' : 'text-left'">
          <div :class="msg.from === 'user' ? 'bg-green-100' : 'bg-gray-100'" class="inline-block px-3 py-2 rounded">
            {{ msg.text }}
          </div>
        </div>
      </div>

      <div class="p-2 border-t">
        <input
          v-model="input"
          @keyup.enter="send"
          class="w-full px-3 py-2 border rounded text-sm"
          placeholder="Digite sua mensagem..."
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, nextTick } from 'vue';

const chatOpen = ref(false);
const input = ref('');
const messages = ref<{ from: string; text: string }[]>([
  { from: 'bot', text: 'Olá! Sou o assistente virtual da UCM. Em que posso ajudar?' },
  { from: 'bot', text: '1️⃣ Informações sobre Cursos\n2️⃣ Propinas e Pagamentos\n3️⃣ Inscrição e Matrícula\n...' },
]);

const messageBox = ref<HTMLElement | null>(null);

const toggleChat = () => {
  chatOpen.value = !chatOpen.value;
  nextTick(() => {
    messageBox.value?.scrollTo({ top: messageBox.value.scrollHeight });
  });
};

const send = async () => {
  const text = input.value.trim();
  if (!text) return;

  messages.value.push({ from: 'user', text });
  input.value = '';

  // Simula chamada à API
  try {
    const response = await fetch('/api/chatbot/answer', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ input: text }),
    });

    const data = await response.json();

    if (data.type === 'question') {
      const res = await fetch(`/api/chatbot/question/${data.id}`);
      const qData = await res.json();
      messages.value.push({ from: 'bot', text: qData.body });
      qData.answers.forEach((ans: any) => {
        messages.value.push({ from: 'bot', text: `👉 ${ans.title}` });
      });
    } else {
      messages.value.push({ from: 'bot', text: data.response });
    }
  } catch (error) {
    messages.value.push({ from: 'bot', text: 'Erro ao se comunicar com o servidor.' });
  }

  nextTick(() => {
    messageBox.value?.scrollTo({ top: messageBox.value.scrollHeight });
  });
};
</script>

<style scoped>
/* Scroll personalizado opcional */
::-webkit-scrollbar {
  width: 4px;
}
::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 2px;
}
</style>