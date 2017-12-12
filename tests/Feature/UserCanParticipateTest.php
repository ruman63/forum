<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCanParticipateTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
        $this->user = factory('App\User')->create();
        $this->be($this->user);
    }
    /** @test */
    public function a_user_can_participate_in_forum_if_logged_in()
    {
        $reply = factory('App\Reply')->make();

        $this->post($this->thread->path('reply'), $reply->toArray());

        $this->get($this->thread->path())->see($reply->body);
    }
}
