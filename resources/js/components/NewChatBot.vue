<template>
    <div>
        <button class="chat-button" @click="toggleChat">
            <MessageCircleMore class="iconMsg" />
        </button>

        <div v-if="isChatOpen" class="chat-window">
            <div class="chat-header">
                <span>Assistente Virtual UCM</span>
                <button class="close-btn" @click="isChatOpen = false">
                    <i class="pi pi-minus"></i>
                </button>
            </div>

            <div class="chat-body" ref="messageBox">
                <p class="paragraph-intro">
                    Este é o início da sua conversação connosco.
                </p>
                
                <div class="container-message">
                    <!-- Renderizar mensagens dinâmicas -->
                    <div v-for="(msg, index) in messages" :key="index">
                        <!-- Mensagem do bot -->
                        <div v-if="msg.from === 'bot'" class="container-messages">
                            <div class="msg-symbol">
                                <MessageSquareText class="icon-symbol" />
                            </div>
                            <div class="chat-messages">
                                <div class="chat-message bot">
                                    <div class="message">
                                        <div class="msg">{{ msg.text }}</div>
                                    </div>
                                </div>
                                <div class="data-share-msg">
                                    <span>UCM Bot</span>
                                    <div class="separateMsg"></div>
                                    <div class="hours-chat">{{ formatTime(msg.timestamp) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mensagem do usuário -->
                        <div v-else class="chat-message user">
                            <div class="message">
                                <div class="msg">{{ msg.text }}</div>
                            </div>
                            <div class="data-share-msg user-hours">
                                <span>Você</span>
                                <div class="separateMsg"></div>
                                <div class="hours-chat">{{ formatTime(msg.timestamp) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opções de resposta -->
                <!-- <div v-if="showOptions && Object.keys(answersMap).length > 0" class="options">
                    <button 
                        v-for="(answerId, optionNumber) in answersMap" 
                        :key="optionNumber"
                        @click="selectOption(optionNumber)"
                        class="option-btn"
                    >
                        {{ optionNumber }}
                    </button>
                </div> -->

                <!-- Indicador de carregamento -->
                <div v-if="isLoading" class="loading-indicator">
                    <div class="container-messages">
                        <div class="msg-symbol">
                            <MessageSquareText class="icon-symbol" />
                        </div>
                        <div class="chat-messages">
                            <div class="chat-message bot">
                                <div class="message">
                                    <div class="msg typing-animation">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chat-footer">
                <button @click="attachFile" :disabled="isLoading">
                    <Paperclip class="iconFix" />
                </button>
                <input 
                    type="text" 
                    v-model="newMessage" 
                    placeholder="Digite sua mensagem ou número da opção..."
                    @keyup.enter="sendMessage"
                    :disabled="isLoading"
                />
                <button @click="sendMessage" :disabled="isLoading || !newMessage.trim()">
                    <SendHorizonal class="iconSend" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, nextTick } from "vue";
import { MessageCircleMore, MessageSquareText, Paperclip, SendHorizonal } from "lucide-vue-next";

// Tipos
interface Message {
    from: 'user' | 'bot';
    text: string;
    timestamp: Date;
}

interface Answer {
    id: number;
    label?: string;
    title?: string;
}

// Estados reativos
const isChatOpen = ref(false);
const newMessage = ref("");
const messages = ref<Message[]>([]);
const currentQuestionId = ref<number | null>(null);
const answersMap = ref<{ [key: string]: number }>({});
const messageBox = ref<HTMLElement | null>(null);
const isLoading = ref(false);
const showOptions = ref(false);

// Função para alternar o chat
const toggleChat = async () => {
    isChatOpen.value = !isChatOpen.value;
    
    if (isChatOpen.value && messages.value.length === 0) {
        await startChat();
    }
    
    await scrollToBottom();
};

// Iniciar o chat
const startChat = async () => {
    isLoading.value = true;
    
    try {
        const res = await fetch('/api/frontend-chatbot');
        const data = await res.json();

        currentQuestionId.value = data.id;
        
        // Adicionar mensagem principal
        addMessage('bot', data.message);

        // Configurar opções de resposta
        answersMap.value = {};
        showOptions.value = true;
        
        data.answers.forEach((ans: Answer, i: number) => {
            const optionNumber = (i + 1).toString();
            const optionText = `${optionNumber}️⃣ ${ans.label || ans.title}`;
            
            addMessage('bot', optionText);
            answersMap.value[optionNumber] = ans.id;
        });

    } catch (error) {
        console.error('Erro ao iniciar chat:', error);
        addMessage('bot', 'Desculpe, ocorreu um erro ao iniciar o chat. Tente novamente.');
    } finally {
        isLoading.value = false;
    }
};

const addMessage = (from: 'user' | 'bot', text: string) => {
    messages.value.push({
        from,
        text,
        timestamp: new Date()
    });
    nextTick(() => scrollToBottom());
};

const sendMessage = async () => {
    const text = newMessage.value.trim();
    if (!text || isLoading.value) return;

    addMessage('user', text);
    newMessage.value = "";

    const answerId = answersMap.value[text];
    if (!answerId) {
        addMessage('bot', 'Por favor, selecione uma opção válida digitando o número (ex: 1, 2, 3...)');
        return;
    }

    await processAnswer(answerId);
};

const selectOption = async (optionNumber: string) => {
    const answerId = answersMap.value[optionNumber];
    if (!answerId) return;

    addMessage('user', optionNumber);
    
    await processAnswer(answerId);
};

// Processar resposta
const processAnswer = async (answerId: number) => {
    isLoading.value = true;
    showOptions.value = false;

    try {
        const response = await fetch('/api/frontend-chatbot/answer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ input: answerId }),
        });

        const data = await response.json();

        if (data.type === 'question') {
            // Nova pergunta
            const res = await fetch(`/api/frontend-chatbot/question/${data.id}`);
            const qData = await res.json();

            currentQuestionId.value = qData.id;
            addMessage('bot', qData.body);

            answersMap.value = {};
            showOptions.value = true;
            
            qData.answers.forEach((ans: Answer, i: number) => {
                const optionNumber = (i + 1).toString();
                const optionText = `${optionNumber}️⃣ ${ans.title || ans.label}`;
                
                addMessage('bot', optionText);
                answersMap.value[optionNumber] = ans.id;
            });

        } else {
            addMessage('bot', data.response);
            showOptions.value = false;
            
            setTimeout(() => {
                addMessage('bot', 'Posso ajudar com mais alguma coisa? Digite "reiniciar" para começar novamente.');
            }, 2000);
        }

    } catch (error) {
        console.error('Erro ao processar resposta:', error);
        addMessage('bot', 'Erro ao comunicar com o servidor. Tente novamente.');
    } finally {
        isLoading.value = false;
    }
};

const attachFile = () => {
    console.log('Anexar arquivo');
};

const formatTime = (timestamp: Date): string => {
    return timestamp.toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Scroll para o final
const scrollToBottom = async () => {
    await nextTick();
    if (messageBox.value) {
        messageBox.value.scrollTop = messageBox.value.scrollHeight;
    }
};
</script>

<style scoped>
.chat-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--main-color);
    color: white;
    border: none;
    border-radius: 50%;
    padding: 15px;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.chat-button:hover {
    transform: scale(1.05);
}

.chat-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.chat-window {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 450px;
    height: 600px;
    background: white;
    border-radius: 8px;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 2px solid #ddd;
}

.chat-header {
    background: var(--main-color);
    color: white;
    padding: 25px 25px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}

.chat-header span {
    font-size: 1.3rem;
}

.close-btn {
    background: transparent;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
}

.chat-body {
    padding: 10px;
    flex: 1;
    overflow-y: auto;
    max-height: 60vh;
}

.icon-symbol {
    width: 20px;
    color: #fff;
}

.container-messages {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    justify-content: flex-start;
    border: 0px solid black;
}

.msg-symbol {
    background-color: var(--main-color, #aaa);
    height: 40px;
    max-width: 40px;
    min-width: 40px;
    border-radius: 100%;
    margin-right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 30px;
}

.data-share-msg {
    display: flex;
    color: #777;
    font-size: 12px;
    align-items: center;
    margin: 5px 0px;
}

.data-share-msg.user-hours {
    justify-content: flex-end;
}

.separateMsg {
    width: 3px;
    height: 3px;
    background-color: #aaa;
    margin: 5px 3px;
    border-radius: 100%;
}

.chat-message {
    margin: 5px 0;
}

.chat-message .message {
    margin-top: 10px;
}

.chat-message.bot .message {
    display: flex;
    justify-content: flex-start;
}

.chat-message.user .message {
    display: flex;
    justify-content: flex-end;
}

.chat-message .message .msg {
    border-radius: 10px;
    max-width: 70%;
    min-width: 0px;
    padding: 15px;
}

.chat-message.bot .message .msg {
    background: var(--bg-chat, #f5f5f5);
    border-top-left-radius: 0px;
    max-width: 70%;
    min-width: 0px;
}

.chat-message.user .message .msg {
    background: var(--main-color);
    border-top-right-radius: 0px;
    text-align: right;
    color: #fff;
}

.options {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 5px;
    margin: 10px 0;
}

.option-btn {
    margin: 5px 5px 0 0;
    padding: 8px 16px;
    border: none;
    background: var(--main-color);
    color: white;
    cursor: pointer;
    border-radius: 20px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.option-btn:hover {
    opacity: 0.8;
    transform: translateY(-1px);
}

/* Rodapé */
.chat-footer {
    display: flex;
    border-top: 1px solid #ccc;
    padding: 15px 25px;
}

.chat-footer input {
    flex: 1;
    border: none;
    padding: 10px;
    outline: none;
    border-radius: 4px;
    margin: 0 10px;
    border: 1px solid #eee;
}

.chat-footer input:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.chat-footer button {
    background: transparent;
    border: none;
    color: white;
    padding: 10px;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.chat-footer button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.chat-footer button:hover:not(:disabled) {
    background-color: rgba(0, 0, 0, 0.1);
}

.iconSend {
    fill: var(--gray-icons, #666);
    stroke-width: 1;
}

.iconFix {
    color: var(--gray-icons, #666);
    stroke-width: 2;
    width: 20px;
    transform: rotate(315deg);
}

.paragraph-intro {
    font-size: 13px;
    text-align: center;
    padding: 10px 0px;
    color: #777;
}

.iconMsg {
    stroke-width: 2;
    width: 25px;
}

/* Animação de digitação */
.loading-indicator {
    margin: 10px 0;
}

.typing-animation {
    display: flex;
    gap: 4px;
    align-items: center;
}

.typing-animation span {
    height: 8px;
    width: 8px;
    background-color: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-animation span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-animation span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Responsividade */
@media (max-width: 768px) {
    .chat-window {
        width: calc(100vw - 2rem);
        height: calc(100vh - 6rem);
        bottom: 5rem;
        right: 1rem;
    }
}
</style>