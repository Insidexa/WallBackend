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

/**
 * Class ImageRepository
 * @package Repositories
 */
class ImageRepository
{

    /**
     * @param \stdClass $wallData
     */
    public static function checkImages ($wallData) {
        if (property_exists($wallData, 'removeImages') && count($wallData->removeImages) > 0)
            Image::destroy($wallData->removeImages);
        static::create($wallData, $wallData->id);
    }
    
    /**
     * @param int $id
     */
    public static function deleteWhereWallId($id) {
        Image::whereWallId($id)->delete();
    }

    /**
     * @param \stdClass $wallData
     * @param int $wallId
     */
    public static function create ($wallData, $wallId) {
        $images = [];

        foreach($wallData->images as $image) {
            if (property_exists($image, 'image')) {
                $images[] = [
                    'path' => ImageFromBase64::convertAndSave($image->image),
                    'wall_id' => $wallId,
                    'name' => ''
                ];
            }
        }

        Image::insert($images);
    }
}