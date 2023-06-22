<?php

use Illuminate\Http\Response;

if (!function_exists('handleException')) {
    /**
     * Summary of handleException
     * @param Throwable $th
     * @return Illuminate\Http\JsonResponse
     */
    function handleException(\Throwable $th)
    {
        $code = (array_key_exists($th->getCode(), Response::$statusTexts)) ? $th->getCode() : 400;

        return response()->json([
            "status"  => false,
            "message" => $th->getMessage()
        ], $code);
    }
}