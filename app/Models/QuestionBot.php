<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBot extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'text',
        'type', // 'text' ou 'button'
        'is_start', // boolean: indica se é a pergunta inicial
        'active',   // boolean: pergunta está ativa?
    ];

    public function options()
    {
        return $this->hasMany(OptionBot::class);
    }
}
