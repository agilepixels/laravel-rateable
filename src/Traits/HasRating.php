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

        $rate = Rating::where(['rateable_type' => get_class($this), 'rateable_id' => $this->id, 'author_id' => $author->id])->first();

        if (empty($rate)) {
            $rate = new Rating(['rating' => $rating]);
            $rate->author()->associate($author);
            $rate->rateable()->associate($this);
        }

        $rate->rating = $rating;
        $rate->save();

        if ($body) {
            $rate->createComment($author, $body);
        }

        return $rate;
    }

    /**
     * Get a rating for this model
     *
     * @param Model $author
     * @return Rating
     * @throws InvalidRating
     */
    public function getRating(Model $author)
    {
        $rate = Rating::where(['rateable_type' => get_class($this), 'rateable_id' => $this->id, 'author_id' => $author->id])->first();
        if (!empty($rate->rating)) return $rate->rating;

        return $rate;
    }


    /**
     * Remove a new rating for this model
     *
     * @param int $rating
     * @param Model $author
     * @param string|null $body
     * @return Rating
     * @throws InvalidRating
     */
    public function removeRating(int $rating, Model $author)
    {
        if ($rating != 0) {
            throw InvalidRating::notInRange($rating);
        }

        $rate = Rating::where(['rateable_type' => get_class($this), 'rateable_id' => $this->id, 'author_id' => $author->id])->first();

        if (!empty($rate)) {
            $rate->delete();
        }

        // TODO remove comment

        return $rate;
    }
}
