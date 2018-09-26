<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     * @return void
     */
    public function create_event_test()
    {
        $commandData = ["name"=>"test"];
        $this->artisan('create:event', $commandData)
            ->expectsOutput('a new event has been created ...')
            ->assertExitCode(0);
        $this->assertDatabaseHas('events', [
            "name" => "test"
        ]);
    }
}
