<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AvatarsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_guest_cannot_upload_avatar()
    {
        $this->withExceptionHandling()
            ->json('POST', '/api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_not_upload_invalid_avatar_image()
    {
        $this->withExceptionHandling()->signIn();

        $this->json("POST", '/api/users/'. auth()->id() . '/avatar', [
            'avatar' => 'not-an-image',
        ])->assertStatus(422);
    }

    /** @test */
    public function authenticated_user_can_upload_a_valid_image_avatar()
    {
        $this->signIn();
        
        Storage::fake('public');

        $this->json("POST", '/api/users/'. auth()->id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
        ]);
                
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
        $this->assertEquals('avatars/'.$file->hashName(), auth()->user()->avatar_path);
    }
}
