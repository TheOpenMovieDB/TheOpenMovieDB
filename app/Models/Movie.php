<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Auditable;

/**
 * @mixin IdeHelperMovie
 */
final class Movie extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory;
    use HasTableName;
    use Auditable;

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
        'user_id'
    ];

    /**
     * @return BelongsToMany<Genre>
     */


    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => config('system.name'),
            'email' => config('system.email'),
            'id' => 1,
        ]);
    }

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
        return $this->belongsToMany(Person::class)
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
