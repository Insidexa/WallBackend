<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Repositories\LikeRepository;
use Socket\ZMQSend;

class LikeController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $like = LikeRepository::like($request->all());
        ZMQSend::send([
            'action' => 'client_like',
            'response' => $like
        ]);
    }
}
