<?php

namespace App\Jobs;

use App\Mail\TestMailables;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $emails;

    protected $userID;
    public $tries = 4;
    public $backoff = 5;

    public $retryAfter = 10;

    // public $deleteWhenMissingModels = true;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->emails as $key => $email) {
            Mail::raw('Hi, welcome user!', function ($message) use ($email) {
                $message->to($email)
                    ->subject("New User");
            });

            dump($email);
        }

    }

    public function failed(\Throwable $exception)
    {
        info($exception->getMessage());
        // $this->fail($exception);
    }
}