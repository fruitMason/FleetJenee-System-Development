<?php

namespace App\Jobs;

use App\Helpers\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Services\SMSService;

class SMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $phone_number;

        /**
     * Create a new job instance.
     *
     * @param string $message
     * @param string $phone_number
     */
    
    public function __construct($message, $phone_number)
    {
        $this->message = $message;

        $this->phone_number = $phone_number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SMSService $smsService)
    {
        Log::info('Sending SMS from SMSJob');

        try {
            // Format the phone number to E.164 format
            if (substr($this->phone_number, 0, 1) !== '+') {
                $this->phone_number = '+233' . ltrim($this->phone_number, '0');
            }

            $smsService->sendSMS($this->phone_number, $this->message);

            Log::info('SMS sent successfully to ' . $this->phone_number);
        } catch (\Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
        }
    }
}
