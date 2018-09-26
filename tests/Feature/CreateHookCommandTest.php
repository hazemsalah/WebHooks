<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function create_a_webhook_with_existing_event_name()
    {
        Event::create([
            'name' => "test"

        ]);
        $this->artisan('create:hook', [
            "event_name" => "test",
            "callback_url" => "http://example.com"
        ])->expectsOutput('a new webhook has been created ...')
            ->assertExitCode(0);

        $this->assertDatabaseHas('webhooks', [
            "event_name" => "test",
            "callback_url" => "http://example.com"
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function create_a_webhook_with_non_existing_event_name()
    {
        $this->artisan('create:hook', [
            "event_name" => "test1",
            "callback_url" => "http://example.com"
        ])->expectsOutput('Event name not found , please create an event first')
            ->assertExitCode(0);
    }
}
