<?php

namespace AgilePixels\Rateable\Traits;

/**
 * Trait RatingCalculations
 * @package AgilePixels\Rateable\Traits
 *
 * @mixin HasRating
 * @mixin AddsRating
 */

trait RatingCalculations
{
    /**
     * Calculate the average rating for this model
     *
     * @return mixed
     */
    public function averageRating() : float
    {
        return $this->ratings()->exists() ? $this->ratings()->avg('rating') : 0;
    }

    /**
     * Sum all the ratings for this model
     *
     * @return mixed
     */
    public function sumRating() : float
    {
        return $this->ratings()->sum('rating');
    }

    /**
     * Calculate the average rating for this model as a percentage
     *
     * @return float|int
     */
    public function averageRatingAsPercentage() : float
    {
        $range = config('rateable.minimum') > 0 ? config('rateable.maximum') - config('rateable.minimum') : config('rateable.maximum');
        return ($this->ratings()->count() * $range) != 0 ? $this->sumRating() / ($this->ratings()->count() * $range) * 100 : 0;
    }

    /**
     * Get the models average rating.
     *
     * @return float
     */
    public function getAverageRatingAttribute() : float
    {
        return $this->averageRating();
    }

    /**
     * Get the models sum of the ratings.
     *
     * @return float
     */
    public function getSumRatingAttribute() : float
    {
        return $this->sumRating();
    }

    /**
     * Get the models average rating as percentage.
     *
     * @return float
     */
    public function getAverageRatingAsPercentageAttribute() : float
    {
        return $this->averageRatingAsPercentage();
    }
}
