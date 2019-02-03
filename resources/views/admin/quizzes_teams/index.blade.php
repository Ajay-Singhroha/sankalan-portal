@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between items-baseline">
            <div class="flex-no-shrink flex items-baseline pr-4">
                <h2 class="text-xl font-normal">Quiz Participations</h2>
                <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $quizzes_teams->total() }}</span>
            </div>
            <get-routes class="flex" 
                select-classes="control is-sm max-w-64"
                route-name="admin.quizzes.teams.index" 
                value="{{ $quiz ? $quiz->slug : '' }}">
                <option value="" selected>All</option>
                @foreach ($quizzes as $eachQuiz)
                    <option value="{{ $eachQuiz->slug}}">{{ ucwords($eachQuiz->title) }}</option>
                @endforeach
                <a slot="button" slot-scope="{link}" :href="link" class="btn is-blue is-sm ml-2 inline-flex items-center">Filter</a>
            </get-routes>
        </div>
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-thin text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Team</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Quiz</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Questions</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Responses</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Started</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Finished</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes_teams as $quiz_team)
                <tr class="border-t hover:bg-grey-lighter" is="quiz-team-row" :data-quiz-team="{{$quiz_team}}">
                    <template slot-scope="{quiz, team, responses_count, start_time, finish_time, onComplete}">
                        <td class="table-fit text-left pl-6 py-2 text-sm">
                            <span v-if="quiz.isSubmitted" class="p-1 ml-1 rounded bg-red text-white font-thin text-xs uppercase leading-none">Submitted</span>
                            <span v-else-if="quiz.isStarted" class="p-1 ml-1 rounded bg-green text-white font-thin text-xs uppercase leading-none">Started</span>
                            <span v-else class="p-1 ml-1 rounded bg-grey font-thin text-xs uppercase leading-none">Waiting</span>
                        </td>
                        <td class="table-fit text-left px-4 py-2" v-text="team.name"></td>
                        <td class="text-left px-4 py-2">
                            <a href="#" v-text="quiz.title" class="capitalize link"></a>
                            <span v-if="quiz.isActive" class="p-1 ml-1 rounded bg-green text-white font-normal text-xs uppercase leading-none">LIVE</span>
                        </td>
                        <td class="table_fit text-center px-4 py-2">
                            <span class="px-2 py-1 rounded-full bg-grey text-xs" v-text="quiz.questions_count"></span>
                        </td>
                        <td class="table_fit text-center px-4 py-2">
                            <span class="px-2 py-1 rounded-full bg-grey text-xs" v-text="responses_count"></span>
                        </td>
                        <td class="table_fit text-center px-4 py-2">
                            <span v-if="start_time" v-text="start_time"></span>
                        </td>
                        <td class="table_fit text-center px-4 py-2">
                            <span v-if="finish_time" v-text="finish_time"></span>
                        </td>
                    </template>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        {{ $quizzes_teams->links() }}
    </div>
</div>
@endsection