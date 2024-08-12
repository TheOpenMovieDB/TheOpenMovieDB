<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * @return BelongsToMany<Genre>
     */

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * @return BelongsToMany<Person>
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }


    /**
     * @return BelongsToMany<Person>
     */
    public function cast(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, )
            ->wherePivot('role', '=', 'cast');

    }

    /**
     * @return BelongsToMany<Person>
     */
    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)
            ->wherePivot('role', '=', 'crew');

    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

}
