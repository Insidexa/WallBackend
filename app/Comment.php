<?php

namespace App;

use Helpers\UserData;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Comment
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $wall_id
 * @property integer $parent_id
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read mixed $is_liked
 * @property-read mixed $likes
 * @property-read \App\User $user
 * @property-read \App\Wall $wall
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'text', 'wall_id'];
    protected $appends = ['likes', 'is_liked', 'user'];

    public static $rules = [
        'comment' => [
            'parent_id' => 'integer',
            'text' => 'required',
        ],
        'wall_id' => 'integer|required',
    ];

    public function toArray()
    {
        $array = parent::toArray();
        $array['likes'] = $this->likes;
        $array['is_liked'] = $this->is_liked;
        $array['user'] = $this->user;
        return $array;
    }

    public function getIsLikedAttribute()
    {
        return Like::whereUserId(UserData::getUser()->id)
            ->whereType('comment')
            ->whereTypeId($this->id)
            ->count();
    }

    public function getLikesAttribute()
    {
        return Like::whereType('comment')
            ->whereTypeId($this->id)
            ->count();
    }
    
    public function getUserAttribute()
    {
        return User::whereId($this->user_id)->firstOrFail();
    }
    
    public function wall()
    {
        return $this->belongsTo(Wall::class);
    }
}
