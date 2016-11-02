<?php

namespace App;

use Helpers\UserData;
use Illuminate\Database\Eloquent\Model;

use Socket\WallSocket;

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
 * @property-read \App\User $user
 * @property-read mixed $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read mixed $is_liked
 * @property-read mixed $is_no_interesting
 */
class Wall extends Model
{
    protected $fillable = ['user_id', 'text'];
    protected $appends = ['likes', 'is_liked'];

    public static $rules = [
        'text' => 'required|min:1'
    ];

    public function toArray()
    {
        $array = parent::toArray();
        $array['likes'] = $this->likes;
        $array['is_liked'] = $this->is_liked;
        $array['is_no_interesting'] = $this->is_no_interesting;
        return $array;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsLikedAttribute()
    {
        return Like::whereUserId(UserData::getUser()->id)
            ->whereType('wall')
            ->whereTypeId($this->id)
            ->count();
    }

    public function getLikesAttribute()
    {
        return Like::whereType('wall')
            ->whereTypeId($this->id)
            ->count();
    }

    public function getIsNoInterestingAttribute()
    {
        return Ignore::whereUserId(UserData::getUser()->id)
            ->whereWallId($this->id)
            ->count();
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
