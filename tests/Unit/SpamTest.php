<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_validates_body_for_spam()
    {
        $spam = new \App\Spam;

        $this->assertFalse($spam->detect('A Nice spamless message'));

        $this->expectException(\Exception::class);
        
        $spam->detect('A Yahoo Customer Support');
    }
}
