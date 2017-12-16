<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function users_can_view_users_profiles()
    {
        $user = create('App\User');
        $this->get("/profiles/{$user->id}")
            ->assertSee($user->name);
    }

    /** @test */
    public function users_profiles_shows_threads_created_by_them()
    {
        $user = create('App\User');

        $thread = create('App\Thread', ['user_id' => $user->id]);
        $this->get("/profiles/{$user->id}")
            ->assertSee($thread->title);
    }
}
