<?php

namespace Tests\Feature;

use App\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateHookCommandTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     * @return void
     */
    public function create_a_webhook_with_existing_event_name()
    {
        $data = [
        'name' => 'test'

         ];

        $event = Event::create($data);

        $commandData = [
            "event_name" => "test",
            "callback_url" => "http://example.com"
        ];

        $this->artisan('create:hook', $commandData)->expectsOutput('a new webhook has been created ...')
            ->assertExitCode(0);

        $dbData =  $commandData = [
            "event_id" => $event->id,
            "callback_url" => "http://example.com"
        ];

        $this->assertDatabaseHas('webhooks', $dbData);
    }

    /**
     * @test
     * @return void
     */
    public function create_a_webhook_with_non_existing_event_name()
    {
        $commandData = [
            "event_name" => "test1",
            "callback_url" => "http://example.com"
        ];

        $this->artisan('create:hook', $commandData)
            ->expectsOutput('Event name not found , please create an event first')
            ->assertExitCode(0);
    }
}
