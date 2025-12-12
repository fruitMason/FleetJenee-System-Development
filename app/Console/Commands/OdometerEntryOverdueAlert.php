<?php

namespace App\Console\Commands;

use App\Jobs\SMSJob;
use App\Mail\OdometerEntryAlertMail;
use App\Models\Car;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class OdometerEntryOverdueAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:odometer-entry-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Odometer Entry Overdue Alert';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cars = Car::query()->whereHas('odometerHistory')->get();

        if($cars->count() > 0)
        {
            $cars->map( function ($car) {

                $is_past = today()->diffInDays(Carbon::parse($car->getLastOdometerEntry()->created_at));

                $last_odometer_value = $car->getLastOdometerEntry()->new_value;
                $last_odometer_value_diff = $last_odometer_value - 5000;

                if ($last_odometer_value_diff <= 100) {
                    if($car->status != 'due_maintenance'){
                        $car->update(['status' => 'due_maintenance', 'date_due_maintenance' => now()]);
                        $this->sendSMSNotify($car, "Hello {$car->user->full_name()}, your car is due maintenance. You currently have {$last_odometer_value_diff} left in odometer readings before it breaks down. Kindly send the car to maintenance asap. \r\nLogin: " . route('auth.login'));
                        $this->createNotification($car, '[OVERDUE] Due Maintenance', "Hello {$car->user->full_name()}, your car is due maintenance. You currently have {$last_odometer_value_diff} left in odometer readings before it breaks down. Kindly send the car to maintenance asap. \r\nLogin: " . route('auth.login'));
                    }
                }

                if ($is_past >= 4){
//                    $this->sendMailNotify($car, $is_past);
                    $this->sendSMSNotify($car, "Hello {$car->user->full_name()}, it's been over {$is_past} day(s) since you last updated your odometer readings. Kindly login to the autoSpa portal and do so. \r\nLogin: " . route('auth.login'));
                    $this->createNotification($car, '[OVERDUE] Odometer Entry', "Hello {$car->user->full_name()}, it's been over {$is_past} day(s) since you last updated your odometer readings. Kindly login to the autoSpa portal and do so. \r\nLogin: " . route('auth.login'));
                }
            });
        }
    }

    private function sendMailNotify($car, $is_past)
    {
        $message = (new OdometerEntryAlertMail($car, $car->user, $is_past));

        try{
            Mail::to($car->user)->send($message);
        }catch (\Exception $ex){}
    }

    private function sendSMSNotify($car, $message)
    {
        SMSJob::dispatchNow($message, $car->user->mobile);
    }

    private function createNotification($car, $title, $message)
    {
        $notification = Notification::query()->create([
            'title' => $title,
            'body' => $message,
            'to_user_id' => $car->user_id,
            'unread' => true
        ]);
    }
}
