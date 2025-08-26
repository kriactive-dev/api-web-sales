<template>
    <div id="pricing" class="py-6 px-6 lg:px-20 my-2 md:my-6">
        <div class="text-center mb-6">
            <div class="text-surface-900 dark:text-surface-0 font-normal mb-2 text-4xl">Mensalidades</div>
            <span class="text-muted-color text-2xl">Acesso 24h à plataforma virtual com conteúdos atualizados</span>
        </div>

        <div class="grid grid-cols-12 gap-4 justify-between mt-20 md:mt-0">
            <div class="col-span-12 lg:col-span-4 p-0 md:p-4">
                <div class="p-4 flex flex-col border-surface-200 dark:border-surface-600 pricing-card cursor-pointer border-2 duration-300 transition-all cards-hover-main" style="border-radius: 10px">
                    <div class="text-surface-900 dark:text-surface-0 text-center my-8 text-3xl">Diurno</div>
                    <img src="/demo/images/landing/free.svg" class="w-10/12 mx-auto" alt="free" />
                    <div class="my-8 flex flex-col items-center gap-4">
                        <div class="flex items-center">
                            <span class="text-2xl font-bold mr-2 text-surface-900 dark:text-surface-0">50,00MZN</span>
                            <span class="text-surface-600 dark:text-surface-200">Por mês</span>
                        </div>
                        <Button label="adquirir" class="p-button-rounded border-0 ml-4 font-light leading-tight bg-blue-500 text-white" style="background-color: var(--main-color); border: 1px solid var(--main-color);"></Button>
                    </div>
                    <Divider class="w-full bg-surface-200"></Divider>
                    <ul class="my-8 list-none p-0 flex text-surface-900 dark:text-surface-0 flex-col px-8">
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Contato direto com professores e colegas</span>
                        </li>
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Acesso completo à infraestrutura</span>
                        </li>
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Agenda compatível</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 p-0 md:p-4 mt-6 md:mt-0">
                <div class="p-4 flex flex-col border-surface-200 dark:border-surface-600 pricing-card cursor-pointer border-2 duration-300 transition-all cards-hover-main" style="border-radius: 10px">
                    <div class="text-surface-900 dark:text-surface-0 text-center my-8 text-3xl">Noturno</div>
                    <img src="/demo/images/landing/startup.svg" class="w-10/12 mx-auto" alt="startup" />
                    <div class="my-8 flex flex-col items-center gap-4">
                        <div class="flex items-center">
                            <span class="text-2xl font-bold mr-2 text-surface-900 dark:text-surface-0">50,00MZN</span>
                            <span class="text-surface-600 dark:text-surface-200">Por mês</span>
                        </div>
                        <Button label="adquirir" class="p-button-rounded border-0 ml-4 font-light leading-tight bg-blue-500 text-white" style="background-color: var(--main-color); border: 1px solid var(--main-color);"></Button>
                    </div>
                    <Divider class="w-full bg-surface-200"></Divider>
                    <ul class="my-8 list-none p-0 flex text-surface-900 dark:text-surface-0 flex-col px-8">
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Flexibilidade para concilia...</span>
                        </li>
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Aulas presenciais com turm...</span>
                        </li>
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Ambiente seguro e estruturado pa...</span>
                        </li>
                        
                    </ul>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 p-0 md:p-4 mt-6 md:mt-0">
                <div class="p-4 flex flex-col border-surface-200 dark:border-surface-600 pricing-card cursor-pointer border-2 duration-300 transition-all cards-hover-main" style="border-radius: 10px">
                    <div class="text-surface-900 dark:text-surface-0 text-center my-8 text-3xl">Ensino a Distância </div>
                    <img src="/demo/images/landing/enterprise.svg" class="w-10/12 mx-auto" alt="enterprise" />
                    <div class="my-8 flex flex-col items-center gap-4">
                        <div class="flex items-center">
                            <span class="text-2xl font-bold mr-2 text-surface-900 dark:text-surface-0">50,00MZN</span>
                            <span class="text-surface-600 dark:text-surface-200">Por mês</span>
                        </div>
                        <Button label="adquirir" class="p-button-rounded border-0 ml-4 font-light leading-tight bg-blue-500 text-white" style="background-color: var(--main-color); border: 1px solid var(--main-color);"></Button>
                    </div>
                    <Divider class="w-full bg-surface-200"></Divider>
                    <ul class="my-8 list-none p-0 flex text-surface-900 dark:text-surface-0 flex-col px-8">
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Acesso 24h à plataforma virtual</span>
                        </li>
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Economia de tempo e custo...</span>
                        </li>
                        <li class="py-2">
                            <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i>
                            <span class="text-xl leading-normal">Suporte online e tutoria especializada</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>