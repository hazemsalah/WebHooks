<?php

namespace App\Commands;

use App\Event;
use App\Validations\Validator;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Create extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:event 
    {name}
    ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a new event';

    /**
     * Execute the console command.
     * @throws
     * @return mixed
     */
    public function handle()
    {
        Validator::eventNameValidation($this->argument('name'));

        Event::create([
            'name' => $this->argument('name')

        ]);
        $this->info("a new event has been created ...");
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
