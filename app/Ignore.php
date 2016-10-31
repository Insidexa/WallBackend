<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Ignore
 *
 * @property integer $id
 * @property integer $wall_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Ignore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ignore whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ignore whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ignore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ignore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ignore extends Model
{
    protected $fillable = ['wall_id', 'user_id'];
}
