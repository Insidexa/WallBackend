<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 11:51
 */

namespace Repositories;

use App\Image;
use Helpers\ImageFromBase64;

class ImageRepository
{
    public static function deleteWhereWallId($id) {
        Image::whereWallId($id)->delete();
    }

    /**
     * @param $wallData
     * @param $wallId
     */
    public static function create ($wallData, $wallId) {
        $images = [];

        foreach($wallData->images as $image) {
            $images[] = [
                'path' => ImageFromBase64::convertAndSave($image->image),
                'wall_id' => $wallId,
                'name' => ''
            ];
        }

        Image::insert($images);
    }
}