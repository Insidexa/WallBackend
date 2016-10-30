<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Like
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id
 * @property integer $wall_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereUpdatedAt($value)
 * @property string $type
 * @property integer $type_id
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereTypeId($value)
 */
class Like extends Model
{
    protected $fillable = ['user_id', 'type', 'type_id'];
}
