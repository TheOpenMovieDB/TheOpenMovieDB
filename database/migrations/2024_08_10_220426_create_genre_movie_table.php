<?php

declare(strict_types=1);

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre_movie', function (Blueprint $table): void {
            $table->foreignIdFor(Movie::class, 'movie_id')
                ->constrained(Movie::getTableName())
                ->cascadeOnDelete();
            $table->foreignIdFor(Genre::class, 'genre_id')
                ->constrained(Genre::getTableName())
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_movie');
    }
};
