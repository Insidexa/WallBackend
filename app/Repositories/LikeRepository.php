<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:51
 */

namespace Repositories;

use \App\Models\Like;
use Helpers\UserData;

/**
 * Class LikeRepository
 * @package Repositories
 */
class LikeRepository
{
    /**
     * @param int $commentId
     */
    public static function deleteWhereData ($commentId) {
        Like::whereTypeId($commentId)->whereType('comment')->delete();
    }
    
    /**
     * @param int $id
     */
    public static function deleteWhereWallId($id) {
        Like::whereTypeId($id)->whereType('wall')->delete();
    } 
    
    /**
     * @param array $data
     * @return array
     */
    public static function like (array $data) {
        $like = Like::whereTypeId($data['type_id'])
            ->whereUserId(UserData::getUser()->id)
            ->whereType($data['type']);

        if ($like->get()->count() == 0) {
            Like::create([
                'type_id' => $data['type_id'],
                'user_id' => UserData::getUser()->id,
                'type' => $data['type']
            ]);
            $action = true;
        } else {
            $like->delete();
            $action = false;
        }

        return [
            'count' => $like
                ->get()
                ->count(),
            'type' => $data['type'],
            'action' => $action,
            'type_id' => $data['type_id'],
            'wall_id' => (isset($data['wall_id'])) ? $data['wall_id'] : null
        ];
    }
}