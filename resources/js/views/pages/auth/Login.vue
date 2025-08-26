<script setup>
import FloatingConfigurator from '@/components/FloatingConfigurator.vue';
import FloatingChat from '@/components/FloatingChat.vue';
import { ref, onBeforeMount } from 'vue';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';
import router from '../../../router';

const toast = useToast();
const email = ref('');
const password = ref('');
const checked = ref(false);
const submitted = ref(false);
const errorMessage = ref('');

const loginUser = () => {
    submitted.value = true;
    // axios.get(`${baseURL2}/sanctum/csrf-cookie`).then(() => {

    axios
        .post(`/api/login`, {
            email: email.value.toLowerCase(),
            password: password.value
        })
        .then((response) => {
            localStorage.setItem('token', response.data.access_token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
            toast.add({ severity: 'success', summary: 'Successo', detail: 'Message Detail', life: 3000 });
            window.location.href = '/admin/dashboard';
        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
            toast.add({ severity: 'error', summary: `${error.response.data.message}`, detail: 'Message Detail', life: 3000 });
        })
        .finally(() => {
            submitted.value = false;
        });
    // })
};

onBeforeMount(() => {
    const token = localStorage.getItem('token');
    if (token) {
    }
});
</script>

<template>
    <!-- <FloatingConfigurator /> -->
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div style="border-radius: 56px; padding: 0.3rem;">
                <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="border-radius: 53px">
                    <div class="text-center mb-8">
                        <router-link to="/">
                            <img src="/logo.jpg" class="mb-8 w-16 shrink-0 mx-auto" alt="">
                        </router-link>

                        <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Universidade Católica de Moçambique</div>
                        <span class="text-muted-color font-medium">Entre para continuar</span>
                        <div v-if="errorMessage">
                            <Button :label="errorMessage" class="mr-2" severity="danger" />
                        </div>
                    </div>

                    <div>
                        <label for="email1" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Email address" class="w-full md:w-[30rem] mb-8" v-model="email" />

                        <label for="password1" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Password</label>
                        <Password id="password1" v-model="password" placeholder="Password" :toggleMask="true" class="mb-4" fluid :feedback="false"></Password>

                        <div class="flex items-center justify-between mt-2 mb-8 gap-8">
                            <div class="flex items-center">
                                <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
                                <label for="rememberme1">Remember me</label>
                            </div>
                            <!-- <span class="font-medium no-underline ml-2 text-right cursor-pointer text-primary">Forgot password?</span> -->
                        </div>
                        <Button label="Sign In" class="w-full" @click="loginUser" v-if="!submitted"></Button>
                        <div class="text-center mb-5" v-if="submitted">
                            <ProgressSpinner style="width: 50px; height: 50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" aria-label="Custom ProgressSpinner" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <FloatingChat />
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
