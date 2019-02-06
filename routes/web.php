<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'PagesController@index')->name('homepage')->middleware('guest');

Auth::routes();

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/teams', 'TeamController@index')->name('teams');
    Route::post('/teams', 'TeamController@store')->name('teams.store');
    Route::post('/events/{event}/participate', 'EventParticipationController@store')->name('events.participate');
    Route::delete('/events/{event}/participate', 'EventParticipationController@destroy')->name('events.withdraw-part');
    Route::get('/quiz/{quiz}', 'QuizController@show')->name('quizzes.take');
    Route::post('/quiz/{quiz}', 'QuizResponseController@store')->name('quizzes.response.store');
});

Route::group(['prefix' => 'manage', 'middleware' => ['auth','admin'], 'namespace' => 'Admin'], function() {
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::get('events_teams/{event?}', 'EventTeamController@index')->name('admin.events.teams.index');
    Route::get('teams', 'TeamController@index')->name('admin.teams.index');
    Route::get('users', 'UserController@index')->name('admin.users.index');
    Route::get('events', 'EventController@index')->name('admin.events.index');
    Route::post('events/{event}/start', 'EventController@goLive')->name('admin.events.go-live');
    Route::post('events/{event}/end', 'EventController@end')->name('admin.events.end');
    Route::post('events/{event}/teams/{team}/paticipate-active-quiz', 'QuizParticipationController@store')->name('admin.events.teams.allow-active-quiz');
    Route::get('quizzes', 'QuizController@index')->name('admin.quizzes.index');
    Route::post('quizzes/{quiz}/open', 'QuizController@goLive')->name('admin.quizzes.go-live');
    Route::post('quizzes/{quiz}/close', 'QuizController@close')->name('admin.quizzes.close');
    Route::get('quizzes_teams/{quiz?}', 'QuizParticipationController@index')->name('admin.quizzes.teams.index');
    Route::post('quizzes_teams/{quizParticipation}/evaluate', 'QuizParticipationController@evaluate')->name('admin.quizzes.teams.evaluate');
});