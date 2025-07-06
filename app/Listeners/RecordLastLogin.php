<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecordLastLogin
{
    /**
     * Create the event listener.
     */
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {        
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => $this->request->ip(),
            // You can add more fields if needed
            // 'last_login_user_agent' => $this->request->userAgent(),
        ]);
       
    }
}
