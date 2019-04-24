<?php

namespace AgilePixels\Rateable\Traits;

use AgilePixels\Commentable\Comment;
use AgilePixels\Rateable\Exceptions\InvalidRating;
use AgilePixels\Rateable\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait HasRating
{

    use RatingCalculations;

    /**
     * This model has many ratings.
     *
     * @return Collection|MorphMany
     */
    public function ratings()
    {
        return $this->morphMany('AgilePixels\Rateable\Rating', 'rateable');
    }

    /**
     * Create a new rating for this model
     *
     * @param int $rating
     * @param Model $author
     * @param string|null $body
     * @return Rating
     * @throws InvalidRating
     */
    public function createRating(int $rating, Model $author, string $body = null)
    {
        if ($rating < config('rateable.minimum') or $rating > config('rateable.maximum')) {
            throw InvalidRating::notInRange($rating);
        }

        $rating = new Rating(['rating' => $rating]);
        $rating->author()->associate($author);
        $rating->rateable()->associate($this);
        $rating->save();

        if ($body) {
            $rating->createComment($author, $body);
        }

        return $rating;
    }

}
