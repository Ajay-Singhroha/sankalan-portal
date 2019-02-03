<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Collection;
use App\Team;
use App\Event;

class ViewEventsTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_lists_all_particpating_teams()
    {
        $events = create(Event::class, 5);
        $teams = create(Team::class, 3);

        $teams->each(function($team) use ($events){
            $events->random(3)->each(function($event) use($team) {
                $team->participate($event);
            });
        });

        $this->withoutExceptionHandling()->signInAdmin();

        $response = $this->get(route('admin.events.teams.index'))
            ->assertSuccessful();

        $results = $response->viewData('events_teams');
        $viewEvents = $response->viewData('events');

        $this->assertCount(5, $viewEvents);
        $this->assertArrayHasKey('slug', $viewEvents->first()->toArray());
        $this->assertArrayHasKey('title', $viewEvents->first()->toArray());

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(9, $results);
        $results->map->toArray()->each(function($result) {
            $this->assertArrayHasKey('event', $result);
            $this->assertArrayHasKey('team', $result);
            $this->assertArrayHasKey('members', $result['team']);
        });
    }

    /** @test */
    public function admin_lists_all_particpating_teams_filtered_by_events()
    {
        $events = create(Event::class, 3);
        $teams = create(Team::class, 3);

        $events->each(function ($event) use ($teams) {
            $teams->random(2)->each(function ($team) use ($event) {
                $team->participate($event);
            });
        });

        $this->withoutExceptionHandling()->be(create(User::class, 1, ['is_admin' => true]));

        $response = $this->get(route('admin.events.teams.index', $events[0]))
            ->assertSuccessful();
        $results = $response->viewData('events_teams');
        $viewEvents = $response->viewData('events');

        $this->assertCount(3, $viewEvents);
        
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
        $results->each(function($result) use ($events){
            $this->assertEquals($events[0]->id, $result->event_id);
        });
    }
}