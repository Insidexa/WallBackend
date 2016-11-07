<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Helpers\UserData;
use Illuminate\Http\Request;

use App\Http\Requests;
use Repositories\CommentRepository;
use Repositories\LikeRepository;
use Socket\ZMQSend;

class CommentController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), Comment::$rules);

        if ($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $comment = CommentRepository::create($request->all());
        ZMQSend::send([
            'response' => $comment,
            'action' => 'client_add_comment'
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
        if (\Gate::forUser(UserData::getUser())->denies('update-comment', $request->get('user')['id'])) {
            return response([], 403);
        }

        $comment = CommentRepository::update($request->all(), $id);
        ZMQSend::send([
            'response' => $comment,
            'action' => 'client_update_comment'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $wallId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $wallId)
    {
        if (\Gate::forUser(UserData::getUser())->denies('delete-comment', CommentRepository::getUserId($id))) {
            return response([], 403);
        }        
        
        $ids = CommentRepository::delete($id);
        LikeRepository::deleteWhereData($id);
        ZMQSend::send([
            'response' => [
                'wall_id' => $wallId,
                'comment_ids' => $ids
            ],
            'action' => 'client_remove_comment'
        ]);
    }
}
