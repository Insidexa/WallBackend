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
        return Comment::select(['user_id'])->whereId($id)->first()->user_id;
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
     * @return array
     * @throws \Exception
     */
    public static function delete($id)
    {
        $ids = [];

        $comment = Comment::whereParentId($id)->first();
        
        if ($comment !== null) {
            $childComments = $comment->getDescendantsAndSelf()->all();

            foreach ($childComments as $comment) {
                /** @var \App\Comment $comment */
                $ids[] = $comment->id;
            }
        }
        
        Comment::whereId($id)->first()->delete();
        \DB::table('comments')->whereIn('id', $ids)->delete();
        $ids[] = (int)$id;

        Comment::rebuild();

        return $ids;
    }

    /**
     * @param array $commentData
     * @return Comment|\Exception
     */
    public static function create(array $commentData)
    {
        $comment = [
            'user_id' => UserData::getUser()->id,
            'wall_id' => $commentData['wall_id'],
            'text' => $commentData['comment']['text'],
            'parent_id' => $commentData['comment']['parent_id']
        ];

        if ($commentData['comment']['parent_id'] === null) {
            $comment = Comment::create($comment);
            $comment->makeRoot();
        } else {
            $root = Comment::whereId($commentData['comment']['parent_id'])->first();
            $comment = $root->children()->create($comment);
        }
        
        return $comment;
    }
}