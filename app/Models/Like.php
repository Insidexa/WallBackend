<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Like
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property integer $type_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Like extends Model
{
    protected $fillable = ['user_id', 'type', 'type_id'];
}
