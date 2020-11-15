<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function api(Request $request){


        return response() -> json($request);
    }
}
