<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateAndSendPDF;
use App\Jobs\InsertUserFromExcel;
use App\Models\User;
use App\Models\UserDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;
use Dompdf\Options;

class UserDocumentController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xls,xlsx',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $name = Carbon::now()->format("Y-m-d_H:i:s") . "_" . $request->file('file')->hashName();
            $path = $request->file('file')->storeAs('laravel/excel', $name);

            $doc = UserDocument::create([
                'user_id' => auth()->user()->id,
                'type' => "excel",
                'url' => $path,
                'status' => "new",
            ]);

            // push queue
            InsertUserFromExcel::dispatch($doc->id);

            return response()->json([
                "status" => true,
                "data" =>
                [
                    "document" => $doc,
                    "url" => Storage::temporaryUrl(
                        $path,
                        now()->addMinutes(5),
                    )
                ]

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
        }

        // InsertUserFromExcel::dispatch("11");

    }

    public function web()
    {
        return view('pdf.pdf', ['name' => 'Efo']);
    }

    public function generatePDF()
    {
        try {
            // push to queue
            GenerateAndSendPDF::dispatch(auth()->user());

            return response()->json([
                "status" => true,
                "data" => null,
                "message" => "pdf_queued"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
