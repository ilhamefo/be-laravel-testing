<?php

namespace App\Http\Controllers;

use App\Models\SourceOfFund;
use Illuminate\Http\Request;

class SourceOfFundController extends Controller
{
    public function index()
    {
        try {

            return response()->json([
                "status" => true,
                "data" => SourceOfFund::all()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 400);
        }
    }
}
