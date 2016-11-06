<?php

namespace App;

use Helpers\UserData;
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
 * @property-read \App\User $user
 * @property-read mixed $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read mixed $is_liked
 * @property-read mixed $is_no_interesting
 */
class Wall extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'text'];
    /**
     * @var array
     */
    protected $appends = ['likes', 'is_liked'];

    /**
     * @var array
     */
    public static $rules = [
        'text' => 'required|min:1'
    ];

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['likes'] = $this->likes;
        $array['is_liked'] = $this->is_liked;
        $array['is_no_interesting'] = $this->is_no_interesting;
        return $array;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return int
     */
    public function getIsLikedAttribute()
    {
        return Like::whereUserId(UserData::getUser()->id)
            ->whereType('wall')
            ->whereTypeId($this->id)
            ->count();
    }

    /**
     * @return int
     */
    public function getLikesAttribute()
    {
        return Like::whereType('wall')
            ->whereTypeId($this->id)
            ->count();
    }

    /**
     * @return bool
     */
    public function getIsNoInterestingAttribute()
    {
        return Ignore::whereUserId(UserData::getUser()->id)
            ->whereWallId($this->id)
            ->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('rgt', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
