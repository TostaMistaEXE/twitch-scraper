<?php

namespace App\Http\Controllers;

use App\Models\Streamer;
use http\Encoding\Stream;
use Illuminate\Http\Request;

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
}
