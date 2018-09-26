<?php

namespace App\Commands;

use App\Event;
use App\Webhook;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CreateHook extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:hook {event_name} {callback_url}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a new webhook for a specific event';

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
        if (!(preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $this->argument('callback_url')))) {
            $this->info("This is not a valid URL");
            return;
        }

        $event->webhooks()->create([
            'callback_url' => $this->argument('callback_url')
        ]);

        $this->info("a new webhook has been created ...");
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
