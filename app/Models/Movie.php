<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTableName;

final class Movie extends Model
{
    use HasFactory, HasTableName;

    protected $table = 'movies';


}
