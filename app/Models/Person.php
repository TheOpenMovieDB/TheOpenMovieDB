<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Person extends Model
{
    use HasFactory;
    use HasTableName;

    protected $table = 'people';

    protected $fillable = [
        'tmdb_id',
        'imdb_id',
        'name',
        'role',
        'birthday',
        'biography',
        'profile_path',
        'gender',
        'known_for_department',
        'place_of_birth',
        'popularity',
        'is_adult',
    ];

    /**
     * @return BelongsToMany<Movie>
     */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }
}
