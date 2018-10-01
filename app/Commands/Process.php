<?php

namespace App\Commands;

use App\WebhookCallBack;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Process extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'process';

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
        $callbacks = WebhookCallBack::where('status', 'failed')->get();
        $callbacks->each(function ($item, $key) {
            $webhook = $item->webhook()->first();
            $client = new Client();
            try {
                if ($item->status =='failed') {
                    $response = $client->request('POST', $webhook->callback_url, [
                        'form_params' => [
                            'message' => $item->message
                        ]
                    ]);
                    $item->status = 'succeeded';
                    $item->save();
                }
            } catch (\Exception $e) {
                $dividedCode = intdiv($e->getCode(), 100);
                $nextSchedulingTime = [
                    0 => 1,
                    1 => 5,
                    5 => 10,
                    10 => 30,
                    30 => 60,
                    60 => 120,
                    120 => 300,
                    300 => 600,
                    600 => 1440
                ];

                if ($dividedCode == 4 || $dividedCode == 5) {
                    $item->scheduled_waiting_time = $nextSchedulingTime[$item->scheduled_waiting_time];

                    $item->save();
                }
            }
        });
        $this->info('Trial to send callbacks ...');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $callbacks = WebhookCallBack::where('status', 'failed')->get();
        if ($callbacks->isEmpty()) {
            return;
        }
        $schedule->command(static::class)->everyMinute();
    }

}
