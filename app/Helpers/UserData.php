<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 02.11.16
 * Time: 12:38
 */

namespace Helpers;


class UserData
{
    public static function getUser () {
        return \JWTAuth::authenticate();
    }
}