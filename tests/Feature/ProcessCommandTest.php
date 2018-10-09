<?php

namespace Tests\Feature;

use App\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProcessCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * testing the process command success case
     *
     * @test
     */
    public function testing_process_command_success_case()
    {
        $eventData = [
            'name' => 'test'

        ];

        $event = Event::create($eventData);

        $webhookData = [
            "callback_url" => "http://example.com"
        ];

        $webhook = $event->webhooks()->create($webhookData);


        $callbackData = [
            "webhook_id" => $webhook->id,
            "message" => "Hello World"
        ];
        $webhook->webhookcallback()->create($callbackData);
        $this->artisan('process')
            ->expectsOutput('Trial to send callbacks ...')
            ->assertExitCode(0);
        $dbData = [
            "webhook_id" => $webhook->id,
            "message" => "Hello World",
            'status' => "succeeded"
        ];

        $this->assertDatabaseHas('webhook_call_backs', $dbData);
    }

    /**
     * testing the process command failure case
     *
     * @test
     */
    public function testing_process_command_failure_case()
    {
        $eventData = [
            'name' => 'test'

        ];

        $event = Event::create($eventData);

        $webhookData = [
            "callback_url" => "http://google.com"
        ];

        $webhook = $event->webhooks()->create($webhookData);


        $callbackData = [
            "webhook_id" => $webhook->id,
            "message" => "Hello World"
        ];
        $webhook->webhookcallback()->create($callbackData);
        $this->artisan('process')
            ->expectsOutput('Trial to send callbacks ...')
            ->assertExitCode(0);
        $dbData = [
            "webhook_id" => $webhook->id,
            "message" => "Hello World",
            'status' => "failed",
            'scheduled_waiting_time' => 1
        ];

        $this->assertDatabaseHas('webhook_call_backs', $dbData);
    }
}
