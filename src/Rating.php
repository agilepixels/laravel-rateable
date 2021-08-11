<?php

namespace AgilePixels\Rateable;

use AgilePixels\Commentable\Comment;
use AgilePixels\Commentable\Traits\HasComments;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $connection;

    public function __construct()
    {
        $this->connection = config('rateable.database');
    }

    use HasComments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['rating'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['comments'];

    /**
     * The rating belongs to a rateable model
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * The rating belongs to an author
     */
    public function author()
    {
        return $this->morphTo();
    }

    /**
     * Create a new comment for this rating
     *
     * @param Model $author
     * @param string $body
     * @return Comment
     */
    public function createComment(Model $author, string $body)
    {
        $comment = new Comment(['body' => $body]);
        $comment->author()->associate($author);
        $comment->commentable()->associate($this);
        $comment->save();

        return $comment;
    }
}
