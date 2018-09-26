<?php

namespace Tests\Feature;

use App\Event;
use App\Webhook;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DispatchCommandTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     * @return void
     */
    public function create_webhooks_callbacks()
    {
        $eventData = [
            'name' => 'test'

        ];

        $event = Event::create($eventData);

        $webhookData = [
            "callback_url" => "http://example.com"
        ];

        $webhook = $event->webhooks()->create($webhookData);


        $commandData = [
            "event_name" => "test",
            "message" => "Hello World"
        ];

        $this->artisan('dispatch', $commandData)->expectsOutput('Webhooks callbacks are created successfully')
            ->assertExitCode(0);

        $dbData = [
            "webhook_id" => $webhook->id,
            "message" => $commandData['message']
        ];

        $this->assertDatabaseHas('webhook_call_backs', $dbData);
    }

    /**
     * @test
     * @return void
     */
    public function create_a_webhook_callback_with_non_existing_event_name()
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
