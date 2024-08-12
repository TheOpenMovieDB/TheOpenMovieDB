<?php

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
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
