<?php

namespace Tests;

use Symfony\Component\Debug\ExceptionHandler;
use App\Exceptions\Handler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = "http://localhost:8000";
    
    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');
        $this->actingAs($user);
        return $user;
    }
}
