<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Jobs\SecondJob;
use App\Jobs\SendTestEmail;
use App\Mail\TestMailables;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            return new UserResource($request->user());
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'logged_in',
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'credentials_mismatch',
        ], 400);
    }

    public function getLandingFlagging()
    {
        try {
            $user = Auth::user();
            $now =  Carbon::now()->toDateString();

            return response()->json([
                "need_update_phone" => is_null($user->update_phone_at),
                "need_update_email" => is_null($user->update_email_at) || $user->update_email_at < $now,
                "need_update_bank" => is_null($user->update_bank_at) || $user->update_bank_at < $now,
                "need_update_personal_data" => is_null($user->update_personal_data_at) || $user->update_personal_data_at < $now,
                "need_update_home_address" => is_null($user->update_home_address_at) || $user->update_home_address_at < $now,
                "need_update_employment" => is_null($user->update_employment_at) || $user->update_employment_at < $now,
                "need_update_additional_information" => is_null($user->update_additional_information_at) || $user->update_additional_information_at < $now,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 400);
        }
    }

    public function updatePersonalData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'birth_date' => 'required|date',
                'birth_place' => 'required',
                'nik' => 'required|min:15|max:16',
                'npwp' => 'required|min:15|max:16',
                'mother_name' => 'required',
                'gender' => 'required|uuid',
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            $user = User::find(Auth::user()->id)
                ->update([
                    "birth_date" => $validated["birth_date"],
                    "birth_place" => $validated["birth_place"],
                    "nik" => $validated["nik"],
                    "npwp" => $validated["npwp"],
                    "mother_name" => $validated["mother_name"],
                    "gender" => $validated["gender"],
                    "update_personal_data_at" => Carbon::now()->addMonths(6)
                ]);

            return response()->json([
                "status" => true,
                "message" => "updated",
                "data" => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
            //throw $th;
        }
    }

    public function updateHomeAddress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'subdistrict_id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            $user = User::find(Auth::user()->id)
                ->update([
                    "address" => $validated["address"],
                    "subdistrict" => $validated["subdistrict_id"],
                    "update_home_address_at" => Carbon::now()->addMonths(6)
                ]);

            return response()->json([
                "status" => true,
                "message" => "updated",
                "data" => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
            //throw $th;
        }
    }
    public function updateEmployment(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'company_address' => 'required',
                'company_name' => 'required',
                'company_subdistrict_id' => 'required|uuid',
                'gross_income_id' => 'required|uuid',
                'job_title_id' => 'required|uuid',
                'line_of_business_id' => 'required|uuid',
                'occupation_id' => 'required|uuid',
                'source_of_fund_free_text' => 'max:64',
                'source_of_fund_id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            $user = User::find(Auth::user()->id)
                ->update([
                    "company_address" => $validated["company_address"],
                    "company_name" => $validated["company_name"],
                    "company_subdistrict" => $validated["company_subdistrict_id"],
                    "gross_income_id" => $validated["gross_income_id"],
                    "job_title_id" => $validated["job_title_id"],
                    "line_of_business_id" => $validated["line_of_business_id"],
                    "occupation_id" => $validated["occupation_id"],
                    "source_of_fund_free_text" => $validated["source_of_fund_free_text"],
                    "source_of_fund_id" => $validated["source_of_fund_id"],
                    "update_employment_at" => Carbon::now()->addMonths(6)
                ]);

            return response()->json([
                "status" => true,
                "message" => "updated",
                "data" => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
            //throw $th;
        }
    }

    public function searchSubdistrict($keyword)
    {
        try {
            $result = DB::select('WITH location_data AS (
                SELECT
                    sub_districts.ID AS ID,
                    sub_districts.postal_code AS "zipcode",
                    sub_districts.NAME AS "sub_district",
                    districts.NAME AS "district",
                    cities.NAME AS "city",
                    provinces.NAME AS "province",
                    sub_districts.tsv 
                FROM
                    sub_districts
                    JOIN districts ON districts.ID = sub_districts.district_id
                    JOIN cities ON cities.ID = districts.city_id
                    JOIN provinces ON provinces.ID = cities.province_id 
                ) SELECT
                * 
            FROM
                ( SELECT * FROM location_data, to_tsquery( ? ) AS q WHERE ( tsv @@q ) ) AS t1 
            ORDER BY
                ts_rank_cd( t1.tsv, to_tsquery( ? ) ) DESC LIMIT 5', [$keyword, $keyword]);

            return response()->json([
                "status" => true,
                "data" => $result
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function searchSubdistrictByID($id)
    {
        try {
            $result = DB::select('SELECT
            sub_districts.ID AS ID,
            sub_districts.postal_code AS "zipcode",
            sub_districts.NAME AS "sub_district",
            districts.NAME AS "district",
            cities.NAME AS "city",
            provinces.NAME AS "province"
        FROM
            sub_districts
            JOIN districts ON districts.ID = sub_districts.district_id
            JOIN cities ON cities.ID = districts.city_id
            JOIN provinces ON provinces.ID = cities.province_id 
            WHERE sub_districts.ID = ?
            ', [$id]);

            return response()->json([
                "status" => true,
                "data" => $result
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function testmail($id)
    {
        try {
            Bus::chain([
                // here you can do Job Chaining
                function () use ($id) {
                    SendTestEmail::dispatch($id);
                    SecondJob::dispatch($id);
                }
            ])->dispatch();

            return response()->json([
                "status" => true,
                "data" => "email_queued"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function import()
    {
        // Excel::import(new UsersImport, 'users.xlsx');
        try {
            Excel::import(new UsersImport, 'users.xlsx');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
            return response()->json($failures);
        }
    }

    public function getLogo()
    {
        $file_path = 'cdn/404.jpg';

        if (Storage::disk('s3')->exists($file_path)) {
            $file =  Storage::disk('s3')->get($file_path);

            $headers = [
                'Content-Type' => 'image/jpg',
                'filename' => '404.jpg'
            ];

            header_remove("X-Powered-By");
            
            return response($file, 200, $headers);
        }
    }

    public function generateAndSendPDF(){
        
    }
}
