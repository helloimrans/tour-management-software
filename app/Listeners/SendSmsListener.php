<?php

namespace App\Listeners;

use App\Events\SendSmsEvent;
use App\Services\SMSService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendSmsEvent $event): void
    {

        $phone = '88' . $event->phone;
        $msg = $event->msg;
        Http::withoutVerifying()
            ->post(env('SMS_API_URL'), [
                'api_key' => env('SMS_API_KEY'),
                'type' => "unicode",
                'contacts' => $phone,
                'senderid' => env('SMS_SENDER_ID'),
                'msg' => $msg,
            ]);
    }
}
