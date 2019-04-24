<?php

namespace AgilePixels\Rateable\Exceptions;

use Exception;

class InvalidRating extends Exception
{

    public static function notInRange(string $rating): self
    {
        return new self("The rating `{$rating}` is invalid. A valid rating must be between " . config('rateable.minimum') . " and " . config('rateable.maximum') . ".");
    }

}