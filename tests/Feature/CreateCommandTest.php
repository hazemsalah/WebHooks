<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function create_event_test()
    {
        $this->artisan('create:event', ["name"=>"test"])
            ->expectsOutput('a new event has been created ...')
            ->assertExitCode(0);
        $this->assertDatabaseHas('events', [
            "name" => "test"
        ]);
    }
}
