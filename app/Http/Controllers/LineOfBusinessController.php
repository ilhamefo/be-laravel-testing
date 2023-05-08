<?php

namespace App\Http\Controllers;

use App\Models\LineOfBusiness;
use Illuminate\Http\Request;

class LineOfBusinessController extends Controller
{
    public function index()
    {
        try {

            return response()->json([
                "status" => true,
                "data" => LineOfBusiness::all()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 400);
        }
    }
}
