<?php

namespace App\Http\Controllers;

use Helpers\UserData;
use Illuminate\Http\Request;

use App\Http\Requests;
use Socket\ZMQSend;

class IgnoreController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ignore = \App\Ignore::create([
            'user_id' => UserData::getUser()->id,
            'wall_id' => $request->get('id')
        ]);

        ZMQSend::send([
            'response' => $ignore->id,
            'action' => 'client_no_interesting'
        ]);
    }
}
