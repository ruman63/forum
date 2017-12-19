<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('A Nice spamless message'));

        $this->expectException(\Exception::class);
        
        $spam->detect('A Yahoo Customer Support');
    }

    /** @test */
    public function it_checks_for_key_held_down()
    {
        $spam = new Spam;

        $this->expectException(\Exception::class);
        
        $spam->detect('Hello thereeeeeeeeeeeeee!');
    }
}
