<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrnazakController extends Controller
{
    //

    public function web_redirect_payment_callback(Request $request){
        Log::info(json_encode($request->all()));
    }
}
