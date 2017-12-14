<?php

namespace Tests;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = "http://localhost:8000";

    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');
        $this->actingAs($user);
        return $user;
    }
}
