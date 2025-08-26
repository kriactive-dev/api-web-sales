<template>
  <div class="fixed bottom-4 right-4 z-50 font-sans">
    <!-- Botão flutuante -->
    <button
      @click="toggleChat"
      class="w-14 h-14 rounded-full bg-ucmBlue text-white shadow-lg flex items-center justify-center text-2xl hover:bg-ucmBlueDark transition-colors"
    >
      💬
    </button>

    <!-- Janela do chat -->
    <transition name="fade">
      <div
        v-if="chatOpen"
        class="w-[400px] h-[600px] bg-white rounded-lg shadow-2xl flex flex-col overflow-hidden mt-3 border border-gray-200"
      >
        <!-- Cabeçalho -->
        <div class="bg-ucmBlue text-white px-4 py-3 flex justify-between items-center">
          <span class="font-semibold">Assistente Virtual UCM</span>
          <button @click="toggleChat" class="text-white text-lg hover:text-gray-200">✖</button>
        </div>

        <!-- Mensagens -->
        <div class="flex-1 p-4 overflow-y-auto space-y-3" ref="messageBox">
          <div
            v-for="(msg, index) in messages"
            :key="index"
            class="flex"
            :class="msg.from === 'user' ? 'justify-end' : 'justify-start'"
          >
            <div
              :class="msg.from === 'user'
                ? 'bg-ucmBlue text-white rounded-t-xl rounded-bl-xl'
                : 'bg-gray-100 text-gray-800 rounded-t-xl rounded-br-xl'"
              class="px-4 py-2 max-w-[80%] text-sm shadow-sm"
            >
              {{ msg.text }}
            </div>
          </div>
        </div>

        <!-- Input -->
        <div class="p-3 border-t bg-gray-50">
          <input
            v-model="input"
            @keyup.enter="send"
            class="w-full px-4 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:border-ucmBlue focus:ring-1 focus:ring-ucmBlue"
            placeholder="Digite sua mensagem..."
          />
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, nextTick } from 'vue';

const chatOpen = ref(false);
const input = ref('');
const messages = ref<{ from: string; text: string }[]>([]);
const currentQuestionId = ref<number | null>(null);
const answersMap = ref<{ [key: string]: number }>({});
const messageBox = ref<HTMLElement | null>(null);

const toggleChat = () => {
  chatOpen.value = !chatOpen.value;
  if (chatOpen.value && messages.value.length === 0) {
    startChat();
  }
  nextTick(() => {
    messageBox.value?.scrollTo({ top: messageBox.value.scrollHeight });
  });
};

const startChat = async () => {
  const res = await fetch('/api/frontend-chatbot');
  const data = await res.json();

  currentQuestionId.value = data.id;
  messages.value.push({ from: 'bot', text: data.message });

  answersMap.value = {};
  data.answers.forEach((ans: any, i: number) => {
    const opt = `${i + 1}️⃣ ${ans.label}`;
    messages.value.push({ from: 'bot', text: opt });
    answersMap.value[(i + 1).toString()] = ans.id;
  });
};

const send = async () => {
  const text = input.value.trim();
  if (!text) return;

  messages.value.push({ from: 'user', text });
  input.value = '';

  const answerId = answersMap.value[text];
  if (!answerId) {
    messages.value.push({ from: 'bot', text: 'Por favor, selecione uma opção válida (ex: 1, 2, 3...)' });
    return;
  }

  try {
    const response = await fetch('/api/frontend-chatbot/answer', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ input: answerId }),
    });

    const data = await response.json();

    if (data.type === 'question') {
      const res = await fetch(`/api/frontend-chatbot/question/${data.id}`);
      const qData = await res.json();

      currentQuestionId.value = qData.id;
      messages.value.push({ from: 'bot', text: qData.body });

      answersMap.value = {};
      qData.answers.forEach((ans: any, i: number) => {
        const opt = `${i + 1}️⃣ ${ans.title}`;
        messages.value.push({ from: 'bot', text: opt });
        answersMap.value[(i + 1).toString()] = ans.id;
      });
    } else {
      messages.value.push({ from: 'bot', text: data.response });
    }
  } catch {
    messages.value.push({ from: 'bot', text: 'Erro ao comunicar com o servidor.' });
  }

  nextTick(() => {
    messageBox.value?.scrollTo({ top: messageBox.value.scrollHeight });
  });
};
</script>

<style scoped>
/* Cores UCM */
.bg-ucmBlue {
  background-color: #004080;
}
.bg-ucmBlueDark {
  background-color: #003366;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

::-webkit-scrollbar {
  width: 4px;
}
::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 2px;
}
</style>
