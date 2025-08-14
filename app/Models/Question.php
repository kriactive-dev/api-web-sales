<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable = ['title', 'message'];

    public function answers(): HasMany {
        return $this->hasMany(Answer::class);
    }
}
