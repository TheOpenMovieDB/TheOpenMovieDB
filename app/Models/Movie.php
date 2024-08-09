<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Movie extends Model
{
    use HasFactory;
    use HasTableName;

    protected $table = 'movies';


}
