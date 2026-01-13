<?php

namespace App\Jobs;

use App\Mail\MailSendMail;
use App\Models\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $validatedData;
    protected $user;
    protected $senderId;

    /**
     * Create a new job instance.
     *
     * @param array $validatedData
     * @param \App\Models\User $user
     * @param int $senderId - The ID of the user sending the email
     */
    public function __construct(array $validatedData, $user, int $senderId)
    {
        $this->validatedData = $validatedData;
        $this->user = $user;
        $this->senderId = $senderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->user->email)->send(new MailSendMail($this->validatedData));

            Log::info('Email sent successfully', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
            ]);

            SendMail::create([
                'from_email' => env('MAIL_USERNAME'),
                'to_email' => $this->user->email,
                'subject' => $this->validatedData['subject'],
                'body' => $this->validatedData['body'],
                'send_by' => $this->senderId, // Use stored sender ID
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            Log::error('Failed to send email', [
                'user_id' => $this->user->id,
                'error' => $th->getMessage(),
            ]);
            throw $th;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Email job failed', [
            'user_id' => $this->user->id,
            'error' => $exception->getMessage(),
        ]);

        SendMail::create([
            'from_email' => env('MAIL_USERNAME'),
            'to_email' => $this->user->email,
            'subject' => $this->validatedData['subject'],
            'body' => $this->validatedData['body'],
            'send_by' => $this->senderId,
            'status' => false,
        ]);
    }
}
