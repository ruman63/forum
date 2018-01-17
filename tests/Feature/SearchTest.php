<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function on_search_proper_search_results_are_returned()
    {
        config(['scout.driver' => 'algolia']);
        $search = 'foobar';
        create('App\Thread', [], 2);
        create('App\Thread', ['body' => "body containing the {$search} term."], 2);

        do {
            sleep(0.23);
            $results = $this->getJson("/threads/search?q={$search}")->json();
        } while (empty($results));

        $this->assertCount(2, $results['data']);
    }
}
