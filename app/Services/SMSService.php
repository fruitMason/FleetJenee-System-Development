<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;

use Twilio\Rest\Client;

class SMSService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

     public function sendSMS($to, $message)
    {
        try {
            $this->client->messages->create($to, [
                'from' => config('services.twilio.from'),
                'body' => $message,
            ]);
            Log::info('SMS sent to ' . $to);
        } catch (\Twilio\Exceptions\RestException $e) {
            Log::error('Twilio failed to send SMS: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
            throw $e;
        }
    }
}
