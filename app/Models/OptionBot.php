<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionBot extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'question_bot_id',
        'label',            // texto do botão
        'value',            // valor retornado ao clicar
        'next_question_bot_id', // id da próxima pergunta
    ];

    public function question()
    {
        return $this->belongsTo(QuestionBot::class);
    }

    public function nextQuestion()
    {
        return $this->belongsTo(QuestionBot::class, 'next_question_bot_id');
    }
}
