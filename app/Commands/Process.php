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
                if ($dividedCode == 4 || $dividedCode == 5) {
                    if ($item->scheduled_waiting_time == 0) {
                        $item->scheduled_waiting_time = 1;
                    } else {
                        if ($item->scheduled_waiting_time == 1) {
                            $item->scheduled_waiting_time = 5;
                        } else {
                            if ($item->scheduled_waiting_time == 5) {
                                $item->scheduled_waiting_time = 10;
                            } else {
                                if ($item->scheduled_waiting_time == 10) {
                                    $item->scheduled_waiting_time = 30;
                                } else {
                                    if ($item->scheduled_waiting_time == 30) {
                                        $item->scheduled_waiting_time = 60;
                                    } else {
                                        if ($item->scheduled_waiting_time == 60) {
                                            $item->scheduled_waiting_time = 120;
                                        } else {
                                            if ($item->scheduled_waiting_time == 120) {
                                                $item->scheduled_waiting_time = 300;
                                            } else {
                                                if ($item->scheduled_waiting_time == 300) {
                                                    $item->scheduled_waiting_time = 600;
                                                } else {
                                                    if ($item->scheduled_waiting_time == 600) {
                                                        $item->scheduled_waiting_time = 1440;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
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
