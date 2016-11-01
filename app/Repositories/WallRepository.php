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

/**
 * Class WallRepository
 * @package Repositories
 */
class WallRepository
{
    /**
     * @param $wallData
     * @return \Illuminate\Database\Eloquent\Model|null|WallRepository
     */
    public static function update($wallData) {
        Wall::find($wallData->id)->update([
            'text' => $wallData->text
        ]);
        return static::get($wallData->id);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function get($id) {
        return Wall::whereId($id)->with('user')->with('images')->with('comments')->first();
    }
    
    /**
     * @param int $id
     * @throws \Exception
     */
    public static function delete($id) {
        Wall::find($id)->delete();
    }

    /**
     * @param \stdClass $wallData
     * @return Wall
     */
    public static function create($wallData) {
        return Wall::create([
            'user_id' => 1,
            'text' => $wallData->text
        ]);
    }
}