<?php

namespace App;

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
    protected $appends = ['likes', 'is_liked'];

    public function toArray()
    {
        $array = parent::toArray();
        $array['likes'] = $this->likes;
        $array['is_liked'] = $this->is_liked;
        return $array;
    }

    public function getIsLikedAttribute()
    {
        return Like::whereUserId(1)->whereType('comment')->whereTypeId($this->id)->count();
    }

    public function getLikesAttribute()
    {
        return Like::whereType('comment')->whereTypeId($this->id)->count();
    }
    
    public function user()
    {
        return $this->hasOne(User::class);
    }
    
    public function wall()
    {
        return $this->belongsTo(Wall::class);
    }
}
