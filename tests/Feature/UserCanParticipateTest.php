<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class UserCanParticipateTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    /** @test */
    public function an_unauthorized_user_can_not_create_replies()
    {
        $this->expectException(AuthenticationException::class);
        $this->post('/threads/1/reply', ['body' => 'FooBar']);
    }
    /** @test */
    public function a_user_can_participate_in_forum_if_logged_in()
    {
        $this->be(factory('App\User')->create());
        
        $reply = factory('App\Reply')->make();

        $this->post($this->thread->path('reply'), $reply->toArray());

        $this->get($this->thread->path())->see($reply->body);
    }
}
