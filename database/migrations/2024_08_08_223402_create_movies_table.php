<?php

declare(strict_types=1);

use App\Enums\ReleaseStatus;
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
        Schema::create(Movie::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->string('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('title')->index();
            $table->string('title_sort');
            $table->string('original_language');
            $table->boolean('is_adult');
            $table->string('backdrop_path')->nullable();
            $table->unsignedBigInteger('budget')->nullable();
            $table->string('homepage')->nullable();
            $table->string('original_title')->nullable();
            $table->mediumText('overview')->nullable();
            $table->unsignedInteger('popularity')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('release_date')->nullable();
            $table->unsignedInteger('revenue')->nullable();
            $table->unsignedInteger('runtime')->nullable();
            $table->enum('status', ReleaseStatus::values());
            $table->string('tagline')->nullable();
            $table->unsignedBigInteger('vote_average')->nullable();
            $table->unsignedBigInteger('vote_count')->nullable();
            $table->foreignIdFor(\App\Models\User::class,'user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Movie::getTableName());
    }
};
