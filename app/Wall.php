<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Wall
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Wall whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Wall whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Wall whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Wall whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Wall whereUpdatedAt($value)
 */
class Wall extends Model
{
    //
}
