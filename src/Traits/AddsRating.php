<?php

namespace AgilePixels\Rateable\Traits;

use AgilePixels\Commentable\Comment;
use AgilePixels\Rateable\Exceptions\InvalidRating;
use AgilePixels\Rateable\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait AddsRating
{

    use RatingCalculations;

    /**
     * This model has many ratings.
     *
     * @return Collection|MorphMany
     */
    public function ratings()
    {
        return $this->morphMany('AgilePixels\Rateable\Rating', 'author');
    }

    /**
     * Create a new rating for this model
     *
     * @param int $rating
     * @param Model $rateable
     * @param string|null $body
     * @return Rating
     * @throws InvalidRating
     */
    public function createRating(int $rating, Model $rateable, string $body = null)
    {
        if ($rating < config('rateable.minimum') or $rating > config('rateable.maximum')) {
            throw InvalidRating::notInRange($rating);
        }

        $rating = new Rating(['rating' => $rating]);
        $rating->author()->associate($this);
        $rating->rateable()->associate($rateable);
        $rating->save();

        if ($body) {
            $rating->createComment($this, $body);
        }

        return $rating;
    }
}
