<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:51
 */

namespace Repositories;

use App\Comment;

/**
 * Class CommentRepository
 * @package Repositories
 */
class CommentRepository
{

    /**
     * @param \stdClass $comment
     * @return $this|bool
     */
    public static function update($comment)
    {
        $comment = Comment::find($comment->id)->fill([
            'text' => $comment->text
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
     * @param \stdClass $data
     * @return void
     */
    public static function delete($data)
    {
        Comment::whereId($data->comment_id)
            ->whereWallId($data->wall_id)
            ->delete();
    }

    /**
     * @param \stdClass $commentData
     * @return Comment|\Exception
     */
    public static function create($commentData)
    {
        $parentId = 0;

        if ($commentData->comment->parent_id)
            $parentId = $commentData->comment->parent_id;

        return Comment::create([
            'user_id' => 1,
            'wall_id' => $commentData->wall_id,
            'text' => $commentData->comment->text,
            'parent_id' => $parentId
        ]);
    }
}