<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

class OneTimeLogin extends Command
{

    /**
     * @var string
     */
    public $description = 'Send an one time login reset link to one or multipe users.';

    /**
     * @var string
     */
    public $signature = 'onetimelogin:send {email?}';

    public function handle()
    {
        if (! empty($this->argument('email'))) {
            $this->sendMail($this->argument('email'));
        } else {
            if ($this->confirm('This will send an email to all users, continue?')) {
                User::all()->map(function ($user) {
                    $this->sendMail($user->email);
                });
            }
        }
    }

    protected function sendMail($email)
    {
        Password::sendResetLink(['email' => $email], function (Message $message) {
			$message->from('accounts@christmastop100.nl', 'Christmas top 100');
            $message->subject(trans('onetimelogin.subject'));
        });
    }

}
