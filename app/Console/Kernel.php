<?php

namespace App\Console;

use App\Http\Controllers\NotificationController;
use App\Models\PaymentReceipt;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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

        Schedule::call(function () {
            PaymentReceipt::where("active", true)
                ->where("sale_id", "!=", null)
                ->each(function (PaymentReceipt $payment_receipt) {
                    (new NotificationController())->notifyAccounts(
                        $payment_receipt->sale,
                        "proof_of_payment",
                        amount: $payment_receipt->amount
                    );
                });
        })->hourly()->between('6:00', '14:00');
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
