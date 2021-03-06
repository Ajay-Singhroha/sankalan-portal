<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\QuizParticipation;
use App\Quiz;
use App\Question;
use App\AnswerChoice;


class EvaluateQuizTest extends TestCase
{

    use RefreshDatabase;
    
    /** @test */
    public function all_quiz_participating_teams_are_evaluated_at_once()
    {
        $quiz = create(Quiz::class);

        $questions = create(Question::class, 5, [
            'quiz_id' => $quiz->id,
            'positive_score' => 4,
            'negative_score' => 1
        ]);

        $questions->each(function ($question) {
            $choices = create(AnswerChoice::class, 4, ['question_id' => $question->id]);
            $question->update(['correct_answer_keys' => $choices->random()->key]);
        });

        $participations = create(QuizParticipation::class, 4, ['quiz_id' => $quiz->id]);
        $participations->each->recordResponses($this->generateResponses($quiz->questions));

        $json = $this->withoutExceptionHandling()
            ->signInAdmin()
            ->postJson(
                route('admin.quizzes.evaluate', $quiz)
            )->assertSuccessful()->json();

        $this->assertArrayHasKey('status', $json);
        $this->assertArrayHasKey('message', $json);
        $this->assertArrayHasKey('scores', $json);

        $participations->map->fresh()->each(function($participation) {
            $this->assertNotNull($participation->score);
            $this->assertEquals(20, $participation->score);
        });
    }

    private function generateResponses($questions)
    {
        return $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->correct_answer_keys->random()
            ];
        })->toArray();
    }
}
