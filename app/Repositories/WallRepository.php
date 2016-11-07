<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:50
 */

namespace Repositories;

use \App\Models\Wall;

use Helpers\UserData;

/**
 * Class WallRepository
 * @package Repositories
 */
class WallRepository
{
    public static function getUserId($id) {
        return Wall::select(['user_id'])->whereId($id)->first()->user_id;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all() {
        return Wall::with('user')->with('images')->with('comments')->get();
    }

    /**
     * @param $wallData
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|WallRepository
     */
    public static function update($wallData, $id) {
        Wall::find($id)->update([
            'text' => $wallData['text']
        ]);
        return static::get($id);
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
     * @param array $wallData
     * @return Wall
     */
    public static function create(array $wallData) {
        return Wall::create([
            'user_id' => UserData::getUser()->id,
            'text' => $wallData['text']
        ]);
    }
}