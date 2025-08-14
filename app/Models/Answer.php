<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $fillable = ['question_id', 'label', 'next_question_id', 'final_message', 'type'];

    public function question(): BelongsTo {
        return $this->belongsTo(Question::class);
    }

    public function nextQuestion(): BelongsTo {
        return $this->belongsTo(Question::class, 'next_question_id');
    }}
