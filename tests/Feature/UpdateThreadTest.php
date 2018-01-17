<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;
    public function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling()->signIn();
        $this->thread = create('App\Thread', ['user_id' => auth()->id()]);
    }
    /** @test */
    public function a_thread_requires_body_and_title_to_be_updated()
    {
        $this->patch($this->thread->path(), [
            'title' => 'Changed title',
        ])->assertSessionHasErrors('body');

        $this->patch($this->thread->path(), [
            'body' => 'Changed body',
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function unauthorized_user_cannot_update_thread()
    {
        $this->signIn(create('App\User'));
        $this->patch($this->thread->path(), [
            'title' => 'Changed title',
            'body' => 'Changed body'
        ])->assertStatus(403);

        tap($this->thread->fresh(), function ($new) {
            $this->assertEquals($new->title, $this->thread->title);
            $this->assertEquals($new->body, $this->thread->body);
        });
    }
    
    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $this->patch($this->thread->path(), [
            'title' => 'Changed title',
            'body' => 'Changed body'
        ]);
        tap($this->thread->fresh(), function ($thread) {
            $this->assertEquals($thread->title, 'Changed title');
            $this->assertEquals($thread->body, 'Changed body');
        });
    }
}
