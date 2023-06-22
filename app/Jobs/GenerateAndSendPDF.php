<?php

namespace App\Jobs;

use App\Mail\TestMailables;
use App\Models\User;
use App\Models\UserDocument;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateAndSendPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deleteWhenMissingModels = true;

    // public $tries = 4;

    // public $backoff = 5;

    public $timeout = 10;

    public $user;

    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdf = $this->generatePDF($this->user);

        $path = "laravel/user_documents/" . "SUMMARY_TRANSACTION" . "_" . $this->user->email . ".pdf";

        Storage::disk('s3')->put($path, $pdf);
        
        // store to database
        $doc = UserDocument::create([
            'user_id' => $this->user->id,
            'type' => "summary_transaction",
            'url' => $path,
            'status' => "new",
        ]);
        info("Kirim imel");
        // send the email
        Mail::to($this->user->email)->send(new TestMailables($path));
        info("Kirim imel done");
    }

    public function failed(\Throwable $exception)
    {
        info("Failed with exception : " . $exception->getMessage());
        $this->fail();
    }

    public function generatePDF(User $user)
    {
        // Create a new instance of Dompdf
        $dompdf = new Dompdf();

        $options = new Options();

        // Load your HTML content or view
        $html = view('pdf.pdf', ['name' => 'Efo'])->render();

        // Set the HTML content
        $dompdf->loadHtml($html);

        $options->set('defaultFont', 'Arial');

        $dompdf->setOptions($options);
        // Render the HTML as PDF
        $dompdf->render();

        return $dompdf->output();
    }
}
