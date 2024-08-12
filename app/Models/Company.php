<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Company extends Model
{
    use HasFactory;
    use HasTableName;

    protected $table = 'companies';
    protected $fillable = [
        'tmdb_id',
        'name',
        'logo_path',
        'origin_country'
    ];


    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
