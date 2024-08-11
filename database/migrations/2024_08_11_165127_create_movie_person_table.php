<?php

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movie_person', function (Blueprint $table) {
            $table->foreignIdFor(Movie::class, 'movie_id')
                ->constrained(Movie::getTableName())
                ->cascadeOnDelete();
            $table->foreignIdFor(Person::class, 'person_id')
                ->constrained(Person::getTableName())
                ->cascadeOnDelete();
            $table->string('character')->nullable();
            $table->string('credit_id')->nullable();
            $table->integer('order')->nullable();
            $table->string('role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_person');
    }
};
