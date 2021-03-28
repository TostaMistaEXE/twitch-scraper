<?php

namespace App\Http\Controllers;

use App\Models\subs;
use Illuminate\Http\Request;

class CreateSub extends Controller
{
    public function createsub(Request $request)
    {
        $request->validate([
            'element_id'=>'required|unique:subs',
            'element_text'=>'required',
            'streamer'=>'required',
        ]);

        subs::create($request->all());

        return $request->all();
    }
    public function createsubtest(Request $request)
    {
        $request->validate([
            'element_id'=>'required|unique:subs',
            'element_text'=>'required',
            'streamer'=>'required',
        ]);

        subs::create($request->all());

        return $request->all();
    }
}
