<?php

namespace App\Console;

use App\Http\Controllers\NotificationController;
use App\Imports\ClientsImport;
use App\Models\Client;
use App\Models\Sale;
use App\Models\CustomJob;
use App\Models\User;
use App\Models\Referral;
use App\Models\PaymentReceipt;
use App\Models\WhatsappMessage;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

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

        //Proof of Payments
        $schedule->call(function () {
            Log::info("Running Proof of Payments hourly reminder");

            PaymentReceipt::where("active", true)
                ->whereHas('sale', function ($query) {
                    $query->where('status', '!=', 2);
                })
                ->each(function (PaymentReceipt $payment_receipt) {
                    (new NotificationController())->notifyAccounts(
                        $payment_receipt->sale,
                        "proof_of_payment",
                        amount: $payment_receipt->amount
                    );
                });
        })->hourly()->between('6:00', '14:00');

        //Proof of Payments
        $schedule->call(function () {


            Sale::where('status', '>', 0)
                ->whereHas('payables', function ($query) {
                    $query->where('paid', 0);
                })
                ->each(function (Sale $sale) {
                    Log::info("Running payables reminder for Sales Order #{$sale->formattedCode()}");
                    (new NotificationController())->notifyAccounts(
                        $sale,
                        "payables",
                    );
                });
        })->dailyAt('8:00');

        //Custom Jobs
        $schedule->call(function () {

            $jobs = CustomJob::where('status', '<', 2)->get();
            foreach ($jobs as $job) {
                switch ($job->type) {
                    case "PRICELIST_SEND":
                        Log::info("Running Job: Sending Batch Pricelist");
                        break;

                    case "BATCH_SEND":
                        Log::info("Running Job: Sending Batch Template Message");
                        break;
                    case "LATEST_UPLOADS":
                        $clients = Client::where('created_at', '>', Carbon::createFromTimestamp(1776682800))->get();

                        $template = WhatsappMessageTemplate::where('code','introductory_01')->first();
                        $message = "";
                        $template_file = "files/seposale_pricelist.pdf";

                        foreach ($clients as $client) {
                            if (!WhatsappMessage::where('client_id', $client->id)->where('message_type', $template->code)->exists()) {
                                (new NotificationController())->processWhatsappTemplateMessage($template, $client->serial, $message, $template_file);
                            }
                        }
                        break;

                    default:
                        Log::info("Running Job: Unknown Type {$job->type}");
                }

                $content = json_decode($job->content, true);
                $file = $content["file"];

                Excel::import(new ClientsImport($job->type, $content), public_path($file));

                $job->update([
                    'status' => 2
                ]);

                Storage::disk('public_uploads')->delete($file);
            }
        })->everyFifteenMinutes();
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
