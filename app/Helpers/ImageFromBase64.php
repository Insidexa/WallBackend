<?php

namespace Helpers;

use File;

/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 30.10.16
 * Time: 15:12
 */
class ImageFromBase64
{
    public static function convertAndSave ($baseString) {
        $imageRaw = explode(',', $baseString);
        $name = 'image_' . time() . '.jpg';
        $path = public_path('uploads/images/') . $name;
        File::put($path, base64_decode($imageRaw[1]));
        return 'uploads/images/' . $name;
    }
}