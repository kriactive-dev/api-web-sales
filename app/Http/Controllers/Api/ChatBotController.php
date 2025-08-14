<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


use App\Models\Interactions;
use App\Models\Question;
use App\Models\Student;
use App\Notifications\WhatsappNotification;
use App\Trait\WhatsAppRecipient as TraitWhatsAppRecipient;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use WhatsAppRecipient;

class ChatBotController extends Controller
{
    //
        // public function sendwhatsapp($number){
        //     try {
        //         $recipient = new TraitWhatsAppRecipient($number);
        //         $ticket = new WhatsappNotification($number);
        //         Notification::send($recipient,$ticket);
        //     } catch (Exception $e) {
        //         return $e;
        //     }
        // }

        public function getwebhook(Request $request){
            $verify_token = 'sk_test_4eC1f2a0-3b8d-4c5b-9a7f-6c1e2d3f4a5b'; 

            if ($request->hub_mode === 'subscribe' && $request->hub_verify_token === $verify_token) {
                return response($request->hub_challenge, 200); 
            }

            return response('Erro de verificação', 403); 
        }

        public function postwebhook(Request $request){
            $entry = $request->input('entry')[0] ?? null;
            if (!$entry) {
                return response('No data', 400);
            }

            $message = $entry['changes'][0]['value']['messages'][0] ?? null;

            if ($message) {
                $from = $message['from']; 
                $text = $message['text']['body'] ?? ''; 

                $response_text = 'Você disse: ' . $text;

                $this->enviarMensagemWhatsApp($from, $response_text);
            }

            return response()->json(['status' => 'received'], 200);
        }

        public function enviarMensagemWhatsApp($to, $message)
        {
            $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD'; 
            $phone_number_id = '397344770133312'; // 

            $response = Http::withToken($token)->post("https://graph.facebook.com/v22.0/{$phone_number_id}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

            if ($response->successful()) {
                return true;
            }

            return false;
        }


        public function handleWebhook(Request $request)
        {
            try {
                $message = $request->input('entry')[0]['changes'][0]['value']['messages'][0] ?? null;
                if ($message) {
                    $from = $message['from'];
                    $text = $message['text']['body'] ?? '';

                    if (strtolower($text) === 'olá' || strtolower($text) === 'oi' || strtolower($text) === 'ajuda') {
                        $this->sendWhatsAppMessage($from, 'Universidade Católica de Moçambique. Como posso ajudar?', [
                        'Ver cursos' => 'ver_cursos',
                        'Situação academica' => 'situacao_academica',
                        'Situação financeira' => 'situacao_financeira',
                        ]);
                    }
                }
                if (Cache::has("awaiting_student_number_$from")) {
                    $tipo = Cache::pull("awaiting_student_number_$from");
                    return $this->consultarSituacao($from, $text, $tipo);
                }

                if (isset($message['type']) && $message['type'] === 'interactive') {


                    $payload = $message['interactive']['button_reply']['id'] ?? null;

                    switch ($payload) {
                        case 'ver_cursos':
                            $courses = Course::limit(10)->get();
                            $texto = "📚 *Cursos disponíveis:*\n\n";
                            foreach ($courses as $curso) {
                                $texto .= "🔹 *{$curso->name}*\nCódigo: {$curso->code}\nDepartamento: {$curso->department}\n\n";
                            }
                            Interaction::create(['user_phone' => $from,'option' => 'ver_cursos',]);
                            $this->enviarMensagemWhatsApp($from, $texto);
                            break;

                        

                        case 'situacao_academica':
                            Cache::put("awaiting_student_number_$from", 'academica', now()->addSeconds(60));
                            Interaction::create(['user_phone' => $from,'option' => 'situacao_academica',]);
                            $this->enviarMensagemWhatsApp($from, "Por favor, envie o número do seu código de estudante para verificarmos o estado da sua situação academica.");
                            break;

                        case 'situacao_financeira':
                            Cache::put("awaiting_student_number_$from", 'financeira', now()->addSeconds(60));
                            Interaction::create(['user_phone' => $from,'option' => 'situacao_financeira',]);
                            $this->enviarMensagemWhatsApp($from, "Por favor, envie o número do seu código de estudante para verificarmos o estado da sua situação financeira.");
                            break;
                    }

                }

                        

                return response()->json(['status' => 'success']);
            } catch (\Throwable $th) {
                Log::error('Erro ao processar o webhook: ' . $th->getMessage());
            }

            
        }

        private function sendWhatsAppMessage($to, $message, $buttons = [])
        {
            try {
                $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD';
                $phone_number_id = '397344770133312'; 

                $buttonPayload = [];
                foreach ($buttons as $title => $payload) {
                    $buttonPayload[] = [
                        'type' => 'reply',
                        'reply' => [
                            'id' => $payload, 
                            'title' => $title,
                        ]
                    ];
                }

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post("https://graph.facebook.com/v22.0/{$phone_number_id}/messages", [
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
                ]);
                Log::error('Erro ao enviar mensagem1: ' . $response->body());

                if ($response->failed()) {
                    logger('Erro ao enviar mensagem2: ' . $response->body());
                }
            } catch (\Throwable $th) {
                Log::error('Erro ao enviar mensagem3: ' . $th->getMessage());
            }
        }

        private function consultarSituacao($from, $studentNumber, $tipo)
        {
            $student = Student::where('student_number', $studentNumber)->first();

            if (!$student) {
                return $this->enviarMensagemWhatsApp($from, "Número de estudante não encontrado. Verifique e envie novamente.");
            }

            if ($tipo === 'financeira') {
                return $this->enviarMensagemWhatsApp($from, "Situação financeira de {$student->name}: *{$student->financial_status}*.");
            }

            if ($tipo === 'academica') {
                return $this->enviarMensagemWhatsApp($from, "Situação acadêmica de {$student->name}: *{$student->academic_status}*.");
            }
        }

        private function askTrackingCode($to)
        {
            $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD'; 
            $phone_number_id = '397344770133312'; 

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post("https://graph.facebook.com/v22.0/{$phone_number_id}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => "🔎 Por favor, envie o código de rastreio do seu pedido.",
                ],
            ]);

            if ($response->failed()) {
                Log::error('Erro ao pedir código de rastreio: ' . $response->body());
            }
        }

        private function notifyAttendant($to)
        {
            $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD'; 
            $phone_number_id = '397344770133312'; 

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post("https://graph.facebook.com/v22.0/{$phone_number_id}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => "👩‍💼 Um atendente será designado para falar com você em breve. Por favor, aguarde.",
                ],
            ]);

            if ($response->failed()) {
                Log::error('Erro ao responder que irá falar com atendente: ' . $response->body());
            }


        }

        private function sendProductList($to)
        {
            $token = 'EAAQZCPJhz2wYBO5vcfZCCMfvbIOvujugelg0DDGZAHZBC51dhH7P68Xob46OorOFhk05OkvtEvsMniN8i7Q8YgFGVwKZBbLnynHUBboENOX19McBKVXJ8ZC6CFvOAnOJHQPlgpZBUkONpWR30ZAQETfHlULTqXixrXj9OHPkBKrZBBnmatM3CB0p3SlhyCZBXXLbHaTwZDZD';
            $phone_number_id = '397344770133312'; 

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post("https://graph.facebook.com/v22.0/{$phone_number_id}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => "📦 Produtos disponíveis:\n\n1️⃣ Camiseta - 15MZN\n2️⃣ Boné - 10MZN\n3️⃣ Mochila - 30MZN\n\nDigite o número do produto para comprar ou clique em 'Falar com suporte'.",
                ],
            ]);

            if ($response->failed()) {
                Log::error('Erro ao enviar lista de produtos: ' . $response->body());
            }
        }




    public function frontendindex() {
        $question = Question::with('answers')->find(1); // Menu Principal
        return response()->json($question);
    }

    public function frontendquestion($id) {
        $question = Question::with('answers')->findOrFail($id);
        return response()->json($question);
    }

    public function answer(Request $request)
    {
        $request->validate([
            'input' => 'required|integer|exists:answers,id',
        ]);

        $answer = Answer::with('nextQuestion.answers')->findOrFail($request->input);

        if ($answer->nextQuestion) {
            return response()->json([
                'type' => 'question',
                'id' => $answer->nextQuestion->id,
            ]);
        }

        return response()->json([
            'type' => 'answer',
            'response' => $answer->final_message,
        ]);
    }

}
