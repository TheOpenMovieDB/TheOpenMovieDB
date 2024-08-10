<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMovie
 */
final class Movie extends Model
{
    use HasFactory;
    use HasTableName;

    protected $table = 'movies';

    protected $fillable = [
        'tmdb_id',
        'imdb_id',
        'title',
        'title_sort',
        'original_language',
        'is_adult',
        'backdrop_path',
        'budget',
        'homepage',
        'original_title',
        'overview',
        'popularity',
        'poster_path',
        'release_date',
        'revenue',
        'runtime',
        'status',
        'tagline',
        'vote_average',
        'vote_count',
    ];


}
