<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use App\Jobs\ChangeLevel;
use App\Admin\Controllers\Base\MaterialController;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ChangeUserLevel::class,
        Commands\AutoRecv::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
//        $schedule->call(function(){
//            (new MaterialController())->updateList();
//        })->daily();

        $schedule->call(function(){
            (new MaterialController())->updateList();
            (new MaterialController())->uploadVideos();
        })->dailyAt('8:00');

        $schedule->command('change:userLevel')
            ->daily()
            ->appendOutputTo(sprintf('/tmp/chicken/changeLevel/%s.log', date('Y-m-d')));

        $schedule->command('auto:recv')
            ->daily()
            ->appendOutputTo(sprintf('/tmp/chicken/autorecv/%s.log', date('Y-m-d')));

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
