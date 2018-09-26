<?php

namespace App\Commands;

use App\Event;
use App\Webhook;
use App\WebhookCallBack;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Dispatch extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dispatch {event_name} {message}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Dispatches event name with given message.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $event = Event::where('name', $this->argument('event_name'))->first();
        if ($event ==null) {
            $this->info("Event name not found , please create an event first");
            return;
        }

        $webhooks = Webhook::where('event_id', $event->id)->get()->toArray();
        foreach ($webhooks as $webhook) {

            WebhookCallBack::create([
                "webhook_id" => $webhook['id'],
                "message" => $this->argument('message')
            ]);
        }
        $this->info("Webhooks callbacks are created successfully");
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
