<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected $hidden = ['correct_answer_keys'];

    protected $casts = [
        'positive_score' => 'integer',
        'negative_score' => 'integer'
    ];

    public function choices()
    {
        return $this->hasMany(AnswerChoice::class);
    }

    public function setCorrectAnswerKeysAttribute($value) {
        if(!$value) {
            $this->attributes['correct_answer_keys'] = '';
        } else if(is_string($value)) {
            $this->attributes['correct_answer_keys']  = $value;
        } else {
            $this->attributes['correct_answer_keys'] = implode(':', $value);
        }
    }

    public function getCorrectAnswerKeysAttribute($keys)
    {
        return collect(explode(':', $keys));
    } 
}
