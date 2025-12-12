<?php

namespace App\Helpers;

use App\Jobs\SMSJob;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Notifications {

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function dispatchSms($text, $phone, $source = 'Gtrans')
    {
        $message = urlencode($text);
        $num = urlencode("$phone");

        $source = 'Gtrans';
        $url = "https://deywuro.com/api/sms?username=".config()->get('sms.business.goil.username')."&password=".config()->get('sms.business.goil.password')."&source=$source&destination=" . $num . "&message=" . $message;

        Log::info('logging source '. $source);
        Log::info('logging url '. $url);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        Log::info('response from sending sms '.response()->json(json_decode($output)));
        return response()->json(json_decode($output));
    }

    public static function createNotification($user, $title, $message)
    {
        $notification = Notification::query()->create([
            'title' => $title,
            'body' => $message,
            'to_user_id' => $user,
            'unread' => true,
            'user_id'=>Auth::user()->id
        ]);
    }

    public function clear(Request $request)
    {
        $user = Auth::user();
        
        // Clear all notifications for the authenticated user
        Notification::where('to_user_id', $user->id)
            ->update(['unread' => false]);

        return response()->json(['success' => true, 'message' => 'All notifications cleared.']);
    }


    public static function sendSMSNotify($car, $message)
    {
        // SMSJob::dispatchNow($message, $car->user->mobile);
    }
}
