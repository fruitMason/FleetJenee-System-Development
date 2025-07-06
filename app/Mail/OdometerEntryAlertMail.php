<?php

namespace App\Mail;

use App\Models\Car;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OdometerEntryAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $car;
    public $user;
    public $days_late;

    /**
     * Create a new message instance.
     *
     * @param Car $car
     * @param User $user
     */
    public function __construct($car, $user, $days_late)
    {
        $this->car = $car;
        $this->user = $user;
        $this->days_late = $days_late;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.OdometerEntryAlertMail')
            ->subject('[OVERDUE] Odometer Entry');
    }
}
