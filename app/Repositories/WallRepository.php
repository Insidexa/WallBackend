<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:50
 */

namespace Repositories;

use App\Like;
use \App\Wall;

class WallRepository
{

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function get($id) {
        return Wall::whereId($id)->with('user')->with('images')->with('comments')->first();
    }
    
    /**
     * @param $id
     * @throws \Exception
     */
    public static function delete($id) {
        Wall::find($id)->delete();
    }

    /**
     * @param $wallData
     * @return Wall
     */
    public static function create($wallData) {
        return Wall::create([
            'user_id' => 1,
            'text' => $wallData->text
        ]);
    }
}