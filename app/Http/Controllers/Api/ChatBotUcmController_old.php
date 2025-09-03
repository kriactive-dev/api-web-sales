<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OptionBot;
use App\Models\QuestionBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\WhatsAppHelper;


class ChatBotUcmControllerX extends Controller
{
    //
    /**
     * Webhook principal
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Pega mensagem recebida
            $message = $request->input('entry')[0]['changes'][0]['value']['messages'][0] ?? null;
            if (!$message) {
                return response()->json(['status' => 'no message']);
            }

            $from = $message['from'];
            $type = $message['type'] ?? '';
            $text = $message['text']['body'] ?? '';

            // Recupera em qual pergunta o usuário está
            $currentQuestionId = Cache::get("current_question_$from");

      

            if (!$currentQuestionId) {
                // Se o usuário mandou olá, oi, ajuda, ola => inicia menu
                if (in_array(strtolower($text), ['olá', 'oi', 'ajuda', 'ola'])) {
                    $question = QuestionBot::where('is_start', true)->where('active', true)->with('options')->first();
                    if ($question) {
                        $this->sendDynamicQuestion($from, $question);
                        $history = Cache::get("question_history_$from", []);
                        $history[] = $question->id;
                        Cache::put("question_history_$from", $history, now()->addMinutes(10));
                        Cache::put("current_question_$from", $question->id, now()->addMinutes(10));
                        return response()->json(['status' => 'start']);
                    } else {
                        $this->sendWhatsAppMessage($from, "Nenhuma pergunta inicial cadastrada.");
                        return response()->json(['status' => 'no start question']);
                    }
                } else {
                    // Qualquer outro texto: só cumprimenta
                    $this->sendWhatsAppMessage($from, "Olá! Digite 'ajuda' para receber opções.");
                    return response()->json(['status' => 'no session']);
                }
            }

            $question = QuestionBot::with('options')->find($currentQuestionId);

            if ($type === 'interactive') {
                Log::info('interactive');
                // Log::info("User $from clicked a button in question $currentQuestionId");
                // Usuário clicou em um botão
                $payload = $message['interactive']['button_reply']['id'] ?? null;
                if (!$payload) {
                    $payload = $message['interactive']['list_reply']['id'] ?? null;
                }
                Log::info('payload '.$payload);

                // Tratamento de paginação
                $page = Cache::get("current_page_$from", 1);
                if ($payload === 'proximo') {
                    $page++;
                    Cache::put("current_page_$from", $page, now()->addMinutes(10));
                    $this->sendWhatsAppListMessage($from, $question, $page);
                    return response()->json(['status' => 'page_next']);
                }
                if ($payload === 'anterior') {
                    $page = max(1, $page - 1);
                    Cache::put("current_page_$from", $page, now()->addMinutes(10));
                    $this->sendWhatsAppListMessage($from, $question, $page);
                    return response()->json(['status' => 'page_prev']);
                }

                if ($payload === 'voltar') {
                    $history = Cache::get("question_history_$from", []);
                    array_pop($history); // Remove a atual
                    $previousQuestionId = array_pop($history); // Pega a anterior
                    if ($previousQuestionId) {
                        $previousQuestion = QuestionBot::with('options')->find($previousQuestionId);
                        $this->sendDynamicQuestion($from, $previousQuestion);
                        Cache::put("current_question_$from", $previousQuestionId, now()->addMinutes(10));
                        Cache::put("question_history_$from", $history, now()->addMinutes(10));
                    } else {
                        // Se não tem anterior, volta ao início
                        $this->sendWhatsAppMessage($from, "Olá! Digite 'ajuda' para receber opções.");
                        Cache::forget("current_question_$from");
                        Cache::forget("question_history_$from");
                    }
                    Cache::forget("current_page_$from"); // Limpa a página quando volta
                    return response()->json(['status' => 'back']);
                }
                // Log::info("Button clicked by user $from in question $currentQuestionId: $payload");
                if ($payload) {
                    $option = OptionBot::where('question_bot_id', $question->id)
                        ->where('value', $payload)
                        ->first();
                    Log::info("Option selected by user $from in question $currentQuestionId: " . json_encode($option));

                    if ($option && $option->next_question_bot_id) {
                        $nextQuestion = QuestionBot::where('id', $option->next_question_bot_id)->where('active', true)->with('options')->first();
                        if ($nextQuestion) {
                            $this->sendDynamicQuestion($from, $nextQuestion);
                            $history = Cache::get("question_history_$from", []);
                            $history[] = $question->id;
                            Cache::put("question_history_$from", $history, now()->addMinutes(10));
                            Cache::put("current_question_$from", $nextQuestion->id, now()->addMinutes(10));
                            Cache::forget("current_page_$from"); // Reset página ao mudar pergunta
                        } else {
                            $this->sendWhatsAppMessage($from, "Obrigado! Seu atendimento foi finalizado.");
                            Cache::forget("current_question_$from");
                            Cache::forget("question_history_$from");
                            Cache::forget("current_page_$from");
                        }
                    } else {
                        // Fluxo termina aqui
                        $this->sendWhatsAppMessage($from, "Obrigado! Seu atendimento foi finalizado.");
                        Cache::forget("current_question_$from");
                        Cache::forget("question_history_$from");
                        Cache::forget("current_page_$from");
                    }
                    return response()->json(['status' => 'option processed']);
                }
            } else {
                // Pergunta do tipo texto (aberta)
                if ($question->type === 'text') {
                    // Salvar ou processar a resposta do usuário se quiser
                    // Encontrar próxima pergunta, se existir
                    $option = $question->options->first(); // normalmente só uma opção para perguntas abertas
                    if ($option && $option->next_question_bot_id) {
                        $nextQuestion = QuestionBot::where('id', $option->next_question_bot_id)->where('active', true)->with('options')->first();
                        if ($nextQuestion) {
                            $this->sendDynamicQuestion($from, $nextQuestion);
                            $history = Cache::get("question_history_$from", []);
                            $history[] = $question->id;
                            Cache::put("question_history_$from", $history, now()->addMinutes(10));
                            Cache::put("current_question_$from", $nextQuestion->id, now()->addMinutes(10));
                            Cache::forget("current_page_$from"); // Reset página ao mudar pergunta
                        } else {
                            $this->sendWhatsAppMessage($from, "Obrigado! Seu atendimento foi finalizado.");
                            Cache::forget("current_question_$from");
                            Cache::forget("question_history_$from");
                            Cache::forget("current_page_$from");
                        }
                    } else {
                        $this->sendWhatsAppMessage($from, "Obrigado! Seu atendimento foi finalizado.");
                        Cache::forget("current_question_$from");
                        Cache::forget("question_history_$from");
                        Cache::forget("current_page_$from");
                    }
                    return response()->json(['status' => 'text processed']);
                }
            }

            return response()->json(['status' => 'waiting']);
        } catch (\Throwable $e) {
            Log::error('Erro webhook dinâmico: ' . $e->getMessage());
            return response()->json(['status' => 'error']);
        }
    }

    private function sendWhatsAppListMessage($to, $question, $page = 1)
{
    try {
        $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD'; // coloque seu token no .env
        $phone_number_id = '397344770133312'; // coloque seu id no .env

        // Paginação das opções (9 por página, pois o botão "Voltar" ocupa 1 slot)
        $options = $question->options;
        $perPage = 9; // Máximo 9 opções por página (1 slot reservado para "Voltar")
        $total = $options->count();
        $pages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $currentOptions = $options->slice($offset, $perPage);

        $rows = [];
        foreach ($currentOptions as $option) {
            $rows[] = [
                'id' => $option->value,
                'title' => WhatsappHelper::formatRowTitle($option->label),
                'description' => '',
            ];
        }

        // Botões de navegação (se necessário)
        if ($page > 1) {
            $rows[] = [
                'id' => 'anterior',
                'title' => '⬅️ Anterior',
                'description' => '',
            ];
        }
        if ($page < $pages) {
            $rows[] = [
                'id' => 'proximo',
                'title' => '➡️ Próximo',
                'description' => '',
            ];
        }

        // Botão "Voltar" (sempre presente, exceto na pergunta inicial)
        if (!($question->is_start ?? false)) {
            $rows[] = [
                'id' => 'voltar',
                'title' => '🔙 Voltar',
                'description' => '',
            ];
        }

        $footerText = 'Selecione uma opção abaixo:';
        if ($pages > 1) {
            $footerText = "Página $page de $pages - Selecione uma opção:";
        }

        $body = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'body' => [
                    'text' => $question->text,
                ],
                'footer' => [
                    'text' => $footerText,
                ],
                'action' => [
                    'button' => 'Escolher',
                    'sections' => [[
                        'title' => 'Opções',
                        'rows' => $rows
                    ]]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("https://graph.facebook.com/v19.0/{$phone_number_id}/messages", $body);

        if ($response->failed()) {
            Log::error('Erro ao enviar mensagem tipo lista: ' . $response->body());
        }
    } catch (\Throwable $th) {
        Log::error('Erro ao enviar mensagem lista: ' . $th->getMessage());
    }
}

    /**
     * Envia pergunta dinâmica com botões (options)
     */
    // private function sendDynamicQuestion($to, $question)
    // {
    //     if (!$question) return;

    //     // Monta botões das opções
    //     $buttons = [];
    //     foreach ($question->options as $option) {
    //         $buttons[$option->label] = $option->value;
    //     }

    //     // Se for pergunta do tipo botão e houver opções, envia como botões
    //     if ($question->type === 'button' && count($buttons) > 0) {
    //         $this->sendWhatsAppMessage($to, $question->text, $buttons);
    //     } else {
    //         // Senão, envia só o texto
    //         $this->sendWhatsAppMessage($to, $question->text);
    //     }
    // }

    private function sendDynamicQuestion($to, $question)
    {
        if (!$question) return;

        $options = $question->options;
        $count = $options->count();

        $isStart = $question->is_start ?? false;
        if (!$isStart) {
            // Adiciona uma opção fake "Voltar"
            $voltar = new \stdClass();
            $voltar->label = 'Voltar';
            $voltar->value = 'voltar';
            $options->push($voltar);
            $count++;
        }

        if ($question->type === 'button' && $count > 0 && $count <= 3) {
            // Até 3 opções: envia como botões
            $buttons = [];
            foreach ($options as $option) {
                $buttons[$option->label] = $option->value;
            }
            $this->sendWhatsAppMessage($to, $question->text, $buttons);

        } elseif ($count > 3) {
            // 4+ opções: envia como lista paginada
            $page = 1;
            Cache::put("current_page_$to", $page, now()->addMinutes(10));
            $this->sendWhatsAppListMessage($to, $question, $page);

        } else {
            // Sem opções: envia só texto
            $this->sendWhatsAppMessage($to, $question->text);
        }
    }

    /**
     * Envia mensagem WhatsApp (com ou sem botões)
     */
    private function sendWhatsAppMessage($to, $message, $buttons = [])
    {
        try {
            $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD'; // coloque seu token no .env
            $phone_number_id = '397344770133312'; // coloque seu id no .env

            if (count($buttons)) {
                $buttonPayload = [];
                foreach ($buttons as $title => $payload) {
                    $buttonPayload[] = [
                        'type' => 'reply',
                        'reply' => [
                            'id' => $payload,
                            'title' => WhatsappHelper::formatButtonTitle($title),
                        ]
                    ];
                }

                $body = [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'interactive',
                    'interactive' => [
                        'type' => 'button',
                        'body' => [
                            'text' => $message,
                        ],
                        'action' => [
                            'buttons' => $buttonPayload,
                        ],
                    ],
                ];
            } else {
                // Somente texto
                $body = [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post("https://graph.facebook.com/v19.0/{$phone_number_id}/messages", $body);

            if ($response->failed()) {
                Log::error('Erro ao enviar mensagem: ' . $response->body());
            }
        } catch (\Throwable $th) {
            Log::error('Erro ao enviar mensagem: ' . $th->getMessage());
        }
    }

    /**
     * Webhook GET para verificação do Facebook/WhatsApp
     */
    public function getwebhook(Request $request)
    {
        $verify_token = 'sk_test_4eC1f2a0-3b8d-4c5b-9a7f-6c1e2d3f4a5b'; 

        if ($request->hub_mode === 'subscribe' && $request->hub_verify_token === $verify_token) {
            return response($request->hub_challenge, 200);
        }

        return response('Erro de verificação', 403);
    }


}
