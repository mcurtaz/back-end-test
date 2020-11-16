<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// di default laravel aggiunge /api alle rotte in api.php. Per modificare questa impostazione RouteServiceProvider

Route::get('/', 'ApiController@api'); // la rotta sarà /api e poi tutti i parametri passati in get
