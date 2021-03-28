<?php

use App\Models\subs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $streamers = subs::select('streamer')->groupBy('streamer')->get();
    //ultimas 24 horas
    return view('show_streamers', compact('streamers'));
});

Route::get('/{slug}', function ($slug) {

    $subs = subs::where('streamer', $slug)->where("created_at", ">", Carbon::now()->subDay())->get();
    $subCount = subs::where('streamer', $slug)->where("created_at", ">", Carbon::now()->subDay())->count();
    $streamer = $slug;
    //ultimas 24 horas
    return view('welcome', compact('subs', 'subCount', 'streamer'));
});
