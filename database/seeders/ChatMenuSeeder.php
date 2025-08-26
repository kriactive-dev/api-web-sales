<?php

namespace Database\Seeders;

use App\Models\OptionBot;
use App\Models\QuestionBot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
        //
            public function run()
    {
        // 1. Pergunta inicial
        $mainQuestion = QuestionBot::create([
            'text' => "Olá! Sou o assistente virtual da UCM. Em que posso ajudar?\nEscolha uma opção:",
            'type' => 'button',
            'is_start' => true,
            'active' => true,
        ]);

        // 2. Submenus para cada opção principal
        $subMenus = [
            'info_cursos' => [
                'label' => 'Informações sobre Cursos',
                'text' => "Sobre cursos, o que deseja saber?",
                'options' => [
                    ['label' => 'Lista de cursos disponíveis', 'value' => 'lista_cursos'],
                    ['label' => 'Duração e requisitos', 'value' => 'duracao_requisitos'],
                    ['label' => 'Mudança de curso', 'value' => 'mudanca_curso'],
                    ['label' => 'Prazos para candidaturas', 'value' => 'prazos_candidatura'],
                    ['label' => 'Metodologia', 'value' => 'metodologia_curso'],
                ]
            ],
            'propinas_pagamentos' => [
                'label' => 'Propinas e Pagamentos',
                'text' => "Sobre propinas e pagamentos, selecione:",
                'options' => [
                    ['label' => 'Valor das propinas por curso', 'value' => 'valor_propinas'],
                    ['label' => 'Formas de pagamento', 'value' => 'formas_pagamento'],
                    ['label' => 'Estado da dívida', 'value' => 'estado_divida'],
                    ['label' => 'Multas por atraso', 'value' => 'multas_atraso'],
                ]
            ],
            'inscricao_matricula' => [
                'label' => 'Inscrição e Matrícula',
                'text' => "Sobre inscrição e matrícula, escolha:",
                'options' => [
                    ['label' => 'Requisitos de inscrição', 'value' => 'requisitos_inscricao'],
                    ['label' => 'Datas e prazos', 'value' => 'datas_prazos'],
                    ['label' => 'Matrícula online', 'value' => 'matricula_online'],
                    ['label' => 'Trancar matrícula', 'value' => 'trancar_matricula'],
                    ['label' => 'Renovação de matrícula', 'value' => 'renovacao_matricula'],
                ]
            ],
            'exames_notas' => [
                'label' => 'Exames e Notas',
                'text' => "Sobre exames e notas:",
                'options' => [
                    ['label' => 'Calendário de exames', 'value' => 'calendario_exames'],
                    ['label' => 'Locais de exame', 'value' => 'locais_exame'],
                    ['label' => 'Resultado de avaliações', 'value' => 'resultado_avaliacoes'],
                    ['label' => 'Pedido de revisão de nota', 'value' => 'revisao_nota'],
                    ['label' => 'Exames especiais', 'value' => 'exames_especiais'],
                    ['label' => 'Recorrências', 'value' => 'recorrencias_exame'],
                ]
            ],
            'calendario_academico' => [
                'label' => 'Calendário Académico',
                'text' => "Sobre calendário académico:",
                'options' => [
                    ['label' => 'Início e fim do semestre', 'value' => 'inicio_fim_semestre'],
                    ['label' => 'Datas importantes', 'value' => 'datas_importantes'],
                    ['label' => 'Feriados académicos', 'value' => 'feriados_academicos'],
                    ['label' => 'Eventos da universidade', 'value' => 'eventos_universidade'],
                ]
            ],
            'documentos_declaracoes' => [
                'label' => 'Documentos e Declarações',
                'text' => "Sobre documentos e declarações:",
                'options' => [
                    ['label' => 'Declaração de matrícula', 'value' => 'declaracao_matricula'],
                    ['label' => 'Requisição de certificado', 'value' => 'requisicao_certificado'],
                    ['label' => 'Requisição de histórico escolar', 'value' => 'requisicao_historico'],
                    ['label' => 'Como levantar documentos', 'value' => 'levantar_documentos'],
                    ['label' => 'Declaração de notas', 'value' => 'declaracao_notas'],
                    ['label' => 'Pedido de diploma', 'value' => 'pedido_diploma'],
                ]
            ],
            'contato_secretaria' => [
                'label' => 'Contato com a Secretaria',
                'text' => "Contato com a secretaria:",
                'options' => [
                    ['label' => 'Localização e horários', 'value' => 'localizacao_horarios'],
                    ['label' => 'Número de telefone e e-mail', 'value' => 'telefone_email'],
                    ['label' => 'Agendar atendimento presencial', 'value' => 'agendar_atendimento'],
                    ['label' => 'Falar com a secretaria (Whatsapp)', 'value' => 'whatsapp_secretaria'],
                ]
            ],
            'outras_informacoes' => [
                'label' => 'Outras Informações',
                'text' => "Outras informações:",
                'options' => [
                    ['label' => 'Apoio psicopedagógico', 'value' => 'apoio_psicopedagogico'],
                    ['label' => 'Acesso à plataforma Moodle', 'value' => 'acesso_moodle'],
                    ['label' => 'Bolsa de estudos', 'value' => 'bolsa_estudos'],
                    ['label' => 'Estágios e oportunidades', 'value' => 'estagios_oportunidades'],
                ]
            ]
        ];

        // 3. Criar submenus (perguntas filhas) e salvar IDs para as opções
        $submenuQuestions = [];
        foreach ($subMenus as $key => $submenu) {
            $submenuQuestions[$key] = QuestionBot::create([
                'text' => $submenu['text'],
                'type' => 'button',
                'is_start' => false,
                'active' => true,
            ]);
        }

        // 4. Adicionar opções à pergunta inicial, cada uma apontando para submenu correspondente
        foreach ($subMenus as $key => $submenu) {
            OptionBot::create([
                'question_bot_id' => $mainQuestion->id,
                'label' => $submenu['label'],
                'value' => $key,
                'next_question_bot_id' => $submenuQuestions[$key]->id,
            ]);
        }

        // 5. Adicionar opções aos submenus (sem próxima pergunta, fluxo termina aqui)
        foreach ($subMenus as $key => $submenu) {
            foreach ($submenu['options'] as $opt) {
                OptionBot::create([
                    'question_bot_id' => $submenuQuestions[$key]->id,
                    'label' => $opt['label'],
                    'value' => $opt['value'],
                    'next_question_bot_id' => null, // Termina atendimento por aqui
                ]);
            }
        }

        // 6. Adicional: pode adicionar uma tela final para cada submenu, se desejar
    }

    }


