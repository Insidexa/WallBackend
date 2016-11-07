<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUpdatedAt($value)
 */
class Image extends Model
{
    protected $fillable = ['wall_id', 'path', 'name'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($image) {
            $path = public_path() . '/' . $image['path'];
            if (\File::exists($path))
                \File::delete($path);
        });
    }
}
