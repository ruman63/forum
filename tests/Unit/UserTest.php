<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_user_can_fetch_his_last_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply', ['user_id' => $user->id]);
        $this->assertEquals($user->lastReply->id, $reply->id);
    }

    /** @test */
    public function it_can_determine_users_avatar_path()
    {
        $user = create('App\User');

        $this->assertEquals(asset('images/avatars/default.jpg'), $user->avatar_path);

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar_path);
    }
}
