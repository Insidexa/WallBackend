<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:51
 */

namespace Repositories;

use \App\Like;

class LikeRepository
{
    /**
     * @param \stdClass $data
     */
    public static function deleteWhereData ($data) {
        Like::whereTypeId($data->commend_id)->whereType('comment')->delete();
    }
    
    /**
     * @param int $id
     */
    public static function deleteWhereWallId($id) {
        Like::whereTypeId($id)->whereType('wall')->delete();
    } 
    
    /**
     * @param \stdClass $data
     * @return array
     */
    public static function like ($data) {
        $like = Like::whereTypeId($data->type_id)
            ->whereUserId($data->user_id)
            ->whereType($data->type);

        if ($like->get()->count() == 0) {
            Like::create([
                'type_id' => $data->type_id,
                'user_id' => $data->user_id,
                'type' => $data->type
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
            'type' => $data->type,
            'action' => $action,
            'type_id' => $data->type_id,
            'wall_id' => $data->wall_id
        ];
    }
}