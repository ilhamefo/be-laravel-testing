<?php

namespace App\Jobs;

use App\Imports\UsersImport;
use App\Models\UserDocument;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InsertUserFromExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $documentID;

    public $documentID;

    public $document;

    public $deleteWhenMissingModels = true;

    public $tries = 4;

    public $backoff = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->documentID = $id;

        $this->document = UserDocument::findOrFail($this->documentID);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // import user
        Excel::import(new UsersImport, $this->document->url, "s3");

        // if success import to users table, then update the document status
        $this->document->update(["status" => "success"]);

        $users = Excel::toCollection(new UsersImport, $this->document->url, "s3");

        // dump($users);
        $userEmails = [];

        foreach ($users[0] as $key => $user) {
            array_push($userEmails, $user["email"]);
        }

        // InsertUserFromExcel::dispatch($doc->id);
        SendTestEmail::dispatch($userEmails);
    }

    public function failed(\Throwable $exception)
    {
        info($exception->getMessage());

        $this->document->update([
            "status" => "failed",
            "failed_reason" => [
                "message" => $exception->getMessage(),
                "exception" => $exception,
            ]
        ]);

        // $this->fail($exception);
    }
}
