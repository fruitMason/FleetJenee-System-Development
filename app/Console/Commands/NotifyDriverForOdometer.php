<?php

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\User;
use App\Traits\GlobalValueCore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyDriverForOdometer extends Command
{
    use GlobalValueCore;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-driver-for-odometer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check And Send SMS To Driver For Unsubmitted Odometer After 3 Days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('command executed');
        // Get all users who should receive the SMS (adjust as needed)
        $cars = Car::whereDoesntHave('odometerHistories', function ($query) {
            $query->where('created_at', '>=', now()->subDays(4));
        })->where('user_id', '<>', '0')->get();


        foreach ($cars as $car) {
            $car_info = $car->model . ' (' . $car->car_number . ')';
            $user = User::find($car->user_id);


            // Add your specific conditions here
            if ($user) {

                $message = "Odometer data for you car  " . $car_info . " has not been submited in more than 3days. Please submit odometer data"; // Customize your message
                $this->SendSMS_ViaHubtelAPI($user->mobile, $message);
                Log::info('command run ' . now().' - - '.$user->mobile);
                $this->info("SMS sent to {$user->mobile}");
            } else {
                $this->warn("Skipped user {$user->id} - conditions not met");
            }
        }
    }
}
