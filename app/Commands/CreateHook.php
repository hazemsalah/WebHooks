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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $event = Event::where('name', $this->argument('event_name'))->first();
        if ($event !=null) {
            Webhook::create([
                'event_name' => $this->argument('event_name'),
                'callback_url' => $this->argument('callback_url')
            ]);
            $this->info("a new webhook has been created ...");
        } else {
            $this->info("Event name not found , please create an event first");
        }
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
