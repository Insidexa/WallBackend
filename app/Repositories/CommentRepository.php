<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:51
 */

namespace Repositories;

use App\Comment;
use Helpers\UserData;
use Socket\WallSocket;

/**
 * Class CommentRepository
 * @package Repositories
 */
class CommentRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|mixed|static[]
     */
    public static function getUserId ($id) {
        return Comment::select(['user_id'])->find($id)->first()->user_id;
    }
    
    /**
     * @param $comment
     * @param $id
     * @return $this|bool
     */
    public static function update($comment, $id)
    {
        $comment = Comment::find($id)->fill([
            'text' => $comment['text'],
            'user_id' => UserData::getUser()->id
        ]);
        return ($comment->update()) ? $comment : false;
    }

    /**
     * @param int $id
     */
    public static function deleteWhereWallId($id)
    {
        Comment::whereWallId($id)->delete();
    }

    /**
     * @param $id
     * @param $wallId
     */
    public static function delete($id, $wallId)
    {
        Comment::whereId($id)
            ->whereWallId($wallId)
            ->delete();
    }

    /**
     * @param array $commentData
     * @return Comment|\Exception
     */
    public static function create(array $commentData)
    {
        $parentId = 0;

        if (isset($commentData['comment']['parent_id']))
            $parentId = $commentData['comment']['parent_id'];

        return Comment::create([
            'user_id' => UserData::getUser()->id,
            'wall_id' => $commentData['wall_id'],
            'text' => $commentData['comment']['text'],
            'parent_id' => $parentId
        ]);
    }
}