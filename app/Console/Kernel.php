<?php

namespace App\Console;

use App\Models\Fund;
use App\Models\Scheme;
use App\Models\TblRequest;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
           // $scheme = Scheme::find(2);
            /* if(empty($scheme)){
                throw new \Exception('Programme Not Found',404);
            }
            if($scheme->fund_type != 'api'){ //change this to entry
                throw new \Exception('There is no Projection for this programme', 400);
            }
             */
            $date = Carbon::now();                        
            Fund::create([
                "fund_category" => $date->subMonth()->format('Y-m'),
                "scheme_id"=>2   //informal Sector             
            ]);
            //$lastMonth = '2020-07';
            //$total = TblRequest::where(DB::raw('LEFT(payment_date,7)'), '=', $lastMonth )->where('payment_status','paid')->sum('get');            
            //$scheme->wallet->safe_balance = $total;
        })->lastDayOfMonth('23:50');
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
