<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerChoice extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function getIllustrationAttribute($illustration)
    {
        if (!$illustration) {
            return $illustration;
        }

        return asset("/images$illustration");
    }

    public function getKeyAttribute($key)
    {
        return strtolower(trim(
            preg_replace(
                '~[^a-zA-Z0-9]+~',
                '',
                preg_replace('~( |_|\-)+~', ' ', $key)
            )
        ));
    }

    public function isCorrect() {
        return $this->question->correct_answer_keys->contains($this->key);
    }
}
