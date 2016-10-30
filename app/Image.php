<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Image
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $wall_id
 * @property string $path
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereUpdatedAt($value)
 */
class Image extends Model
{
    protected $fillable = ['wall_id', 'path', 'name'];
}
