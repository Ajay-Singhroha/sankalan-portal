<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function normal_users_redirected_to_users_dashboard_after_login()
    {
        $user = create(User::class, 1, ['is_admin' => false]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertRedirect(route('dashboard'));

        $this->assertFalse(Auth::user()->isAdmin());
    }
    
    /** @test */
    public function admins_redirected_to_admins_dashboard_after_login()
    {
        $user = create(User::class, 1, ['is_admin' => true]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(Auth::user()->isAdmin());
    }
}
