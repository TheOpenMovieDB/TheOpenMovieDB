<?php

declare(strict_types=1);

use App\Enums\PersonGender;
use App\Models\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Person::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('tmdb_id')->unique();
            $table->string('imdb_id')->nullable()->unique();
            $table->string('name');
            $table->date('birthday')->nullable();
            $table->text('biography')->nullable();
            $table->string('profile_path')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('known_for_department')->nullable();
            $table->enum('gender', PersonGender::values());
            $table->decimal('popularity', 8, 2)->nullable();
            $table->boolean('is_adult');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Person::getTableName());
    }
};
