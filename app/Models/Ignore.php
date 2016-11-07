<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Ignore
 *
 * @property integer $id
 * @property integer $wall_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ignore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ignore whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ignore whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ignore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ignore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ignore extends Model
{
    protected $fillable = ['wall_id', 'user_id'];
}
