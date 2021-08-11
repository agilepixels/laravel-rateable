While waiting for pull requests into the main repo,

- continuing development of the package as it suites my needs better than the others i found.
- using https://github.com/gjunge/rateit.js on the front end

# Add ratings to Eloquent Models

[![Latest Version on Packagist](https://img.shields.io/badge/packagist-1.0.0-blue.svg?style=flat-square)](https://packagist.org/packages/agilepixels/laravel-rateable)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](https://travis-ci.org/agilepixels/laravel-rateable)
[![StyleCI](https://styleci.io/repos/119671555/shield?branch=master)](https://styleci.io/repos/119671555)
[![Quality Score](https://img.shields.io/scrutinizer/g/agilepixels/laravel-rateable.svg?style=flat-square)](https://scrutinizer-ci.com/g/agilepixels/laravel-rateable)
[![Total Downloads](https://img.shields.io/packagist/dt/agilepixels/laravel-rateable.svg?style=flat-square)](https://packagist.org/packages/agilepixels/laravel-rateable)

Imagine you want to add star ratings to an Eloquent Model. This package enables that feature for you. Ratings can be from 0 to 5 stars, +1/-1 or any other range you like.

This package provides a `HasRatings` and `AddsRatings` traits that, once installed on a model, allows you to do things like this:

```php
// Add a rating for a model
$model->createRating($rating = 4, $author = $user, $body = 'Very nice!');

// Calculate the average rating for a model
$model->averageRating();

// Sum the ratings for a model
$model->sumRating();
```

## Installation

You can install the package via composer:

```bash
composer require agilepixels/laravel-rateable
```

The migrations for the ratings are [loaded automatically](https://laravel.com/docs/5.8/packages#migrations). You can migrate the `ratings` table using:

```bash
php artisan migrate
```

A config file is included to specify the range for the ratings. By default, rating are between 0 and 5. However, you are free to use it otherwise. For instance, ratings like +1 or -1. You can publish the config-file with:

```bash
php artisan vendor:publish --provider="AgilePixels\Rateable\RateableServiceProvider" --tag="config"
```

### Using the trait

To enable the ratings for a model, use the `AgilePixels\Rateable\Traits\HasRatings` trait on the model.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AgilePixels\Rateable\Traits\HasRating;

class Product extends Model
{
    use HasRating;
}
```

If you would like to calculate the ratings for the author model, you may use the `AgilePixels\Rateable\Traits\AddsRatings` trait on your User model (or any other model that is able to add a rating).

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AgilePixels\Rateable\Traits\AddsRating;

class User extends Model
{
    use AddsRating;
}
```

## Usage

### The createRating Method

To create a rating for a model that `HasRatings`, you can use the `creatRating()` method. The method takes two variables: `$rating` and `$author`. The `$rating` can be an integer or a float within the range defined in your config file (default is 0 to 5). The `$author` refers to the model that `AddsRatings` which, in most cases, is your User model.

```php
$product->createRating($rating, $author)
```

Optionally, you can also post a comment for a rating. This can be done through a third string variable called `$body`.

```php
$product->createRating($rating, $author, $body)
```

### The createComment Method

Once a rating is created, you might want to respond to the rating as owner of the web application. This can be done using the `createComment()` method. The method takes two variables: `$author` and `$body`.

```php
$rating->createComment($author, $body)
```

### Calculations

Of course, you may want to display some data about the models ratings. This package provides three methods to do so:

```php
$product->averageRating();
$product->averageRatingAsPercentage();
$product->sumRating();
```

The data is also available as [accessor](https://laravel.com/docs/5.7/eloquent-mutators#defining-an-accessor). You may access the data like this:

```php
$product->average_rating
$product->average_rating_as_percentage
$product->sum_rating
```

## Credits

- [Lex de Willigen](https://github.com/lexdewilligen)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
