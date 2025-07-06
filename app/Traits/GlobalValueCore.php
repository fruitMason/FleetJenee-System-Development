<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait GlobalValueCore
{
    public static function SendSMS_ViaHubtelAPI($destination, $message): string
    {
        $nalo_user = config('smsconfig.nalo_user');
        $nalo_password = config('smsconfig.nalo_keycode');
        $nalo_source = config('smsconfig.nalo_source');

        Log::info('otp-code :payload &from=' . $nalo_source . '&to=' . $destination . '&content=' . $message);
        //$response = Http::get('https://sms.hubtel.com/v1/messages/send?clientsecret=' . $nalo_password . '&clientid=' . $nalo_user . '&from=' . $nalo_source . '&to=' . $destination . '&content=' . $message);

        $response = Http::get('https://sms.nalosolutions.com/smsbackend/clientapi/Resl_MicroSystems/send-message/?username=' . $nalo_user . '&password=' . $nalo_password . '&message=' . $message . '&type=1&destination=' . $destination . '&source=' . $nalo_source . '&dlr=1&callback=httpxxxhe');

        Log::info('otp-code :response ' . $response);

        return $response;
    }
}
