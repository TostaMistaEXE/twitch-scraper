<?php

namespace App\Http\Controllers;

use App\Models\Streamer;
use http\Encoding\Stream;
use Illuminate\Http\Request;
//devia tar na api
class StreamerController extends Controller
{
    public function getAll()
    {
        return Streamer::all();
    }

    public function killAll()
    {
        Streamer::where('run', 1)->update(['run'=>0]);
        return $this->getAll();
    }
    public function changeStatus(Request $request)
    {
        $streamer = Streamer::where('streamer', $request->streamer);
        $streamer->update(['run'=>$request->run]);
        return $streamer->get();
    }
    public function changeOnline(Request $request)
    {
        $streamer = Streamer::where('streamer', $request->streamer);
        $streamer->update(['is_online'=>$request->is_online]);
        return $streamer->get();
    }
    public function create(Request $request){
        return Streamer::create(['streamer'=>$request->streamer]);
    }
}
