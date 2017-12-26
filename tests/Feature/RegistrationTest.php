<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Mail\ConfirmationMail;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_mail_is_sent_to_each_registered_user()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => "John Doe",
            'email' => "Johndoe@example.net",
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        
        Mail::assertSent(ConfirmationMail::class);
    }

    /** @test */
    public function a_user_can_confirm_their_email_using_email_link()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => "John Doe",
            'email' => "Johndoe@example.net",
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $user = User::whereName('John Doe')->first();
        
        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertSessionHas('flash', 'You are now a confirmed User, you can start posting.');
        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }
}
