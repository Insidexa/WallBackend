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
     * @param array $wallData
     */
    public static function checkImages ($wallData) {
        if (isset($wallData['removeImages']) && count($wallData['removeImages']) > 0)
            Image::destroy($wallData['removeImages']);
        static::create($wallData, $wallData['id']);
    }
    
    /**
     * @param int $id
     */
    public static function deleteWhereWallId($id) {
        Image::whereWallId($id)->delete();
    }

    /**
     * @param array $wallData
     * @param int $wallId
     */
    public static function create ($wallData, $wallId) {
        $images = [];

        foreach($wallData['images'] as $image) {
            if (isset($image['image'])) {
                $images[] = [
                    'path' => ImageFromBase64::convertAndSave($image['image']),
                    'wall_id' => $wallId,
                    'name' => ''
                ];
            }
        }

        Image::insert($images);
    }
}