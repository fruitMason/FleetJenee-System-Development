<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $fullName;
    public $email;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $password
     */
    public function __construct($fullName, $email, $password, $loginUrl)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->view('email.user_registration')
            ->subject('Your Account has been Created')
            ->with([
                'fullName' => $this->fullName,
                'email' => $this->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
            ]);
    }
}
