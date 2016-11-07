<?php

namespace App\Http\Controllers;

use App\Models\Wall;
use Helpers\UserData;
use Illuminate\Http\Request;

use App\Http\Requests;
use Repositories\CommentRepository;
use Repositories\ImageRepository;
use Repositories\LikeRepository;
use Repositories\WallRepository;
use Socket\ZMQSend;

class WallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WallRepository::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), Wall::$rules);

        if ($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);
        
        $data = $request->only(['images', 'text']);
        $wall = WallRepository::create($data);
        ImageRepository::create($data, $wall->id);
        ZMQSend::send([
            'response' => WallRepository::get($wall->id),
            'action' => 'client_add_wall'
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Gate::forUser(UserData::getUser())->denies('update-post', WallRepository::getUserId($id))) {
            return response([], 403);
        }

        ImageRepository::checkImages($request->all());
        $wall = WallRepository::update($request->all(), $id);
        ZMQSend::send([
            'response' => $wall,
            'action' => 'client_update_wall'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Gate::forUser(UserData::getUser())->denies('delete-post', WallRepository::getUserId($id))) {
            return response([], 403);
        }
        
        WallRepository::delete($id);
        ImageRepository::deleteWhereWallId($id);
        CommentRepository::deleteWhereWallId($id);
        LikeRepository::deleteWhereWallId($id);
        ZMQSend::send([
            'response' => $id,
            'action' => 'client_remove_wall'
        ]);
    }
}
