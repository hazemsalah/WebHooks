<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInspiringCommand()
    {
        $this->artisan('create:event', ["name"=>"test"])
            ->expectsOutput('a new event has been created ...')
            ->assertExitCode(0);
        $this->assertDatabaseHas('events', [
            "name" => "test"
        ]);
    }
}
