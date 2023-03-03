<?php

namespace App\Jobs;

use App\Mail\Email;
use App\Models\UserPasswordRest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Generate Token
        $token = mt_rand(1000, 9999);

        // Saving Data to Password Reset Table
        UserPasswordRest::create([
            'email' => $this->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Sending EMail with Password Reset View
        // Mail::send('adminreset', ['token' => $token], function (Message $message) use ($email) {
        //     $message->subject('Reset Your Password');
        //     $message->to($email);
        // });
        Mail::to($this->email)->send(new Email($token));
    }
}
