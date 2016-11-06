<?php

namespace App;

use Baum\Node;
use Helpers\UserData;


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
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property-read mixed $is_liked
 * @property-read mixed $likes
 * @property-read mixed $user
 * @property-read \App\Wall $wall
 * @property-read \App\Comment $parent
 * @property-read \Baum\Extensions\Eloquent\Collection|\App\Comment[] $children
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereWallId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 * @mixin \Eloquent
 */
class Comment extends Node
{
    protected $fillable = ['user_id', 'parent_id', 'text', 'wall_id', 'depth', 'lft', 'rgt'];
    protected $appends = ['likes', 'is_liked', 'user'];
    protected $guarded = ['id', 'parent_id', 'lft', 'rgt', 'depth'];

    protected $parentColumn = 'parent_id';

    protected $orderColumn = 'lft';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rgt';

    // 'depth' column name
    protected $depthColumn = 'depth';

    public static $rules = [
        'comment.parent_id' => 'integer',
        'comment.text' => 'required|min:1',
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
