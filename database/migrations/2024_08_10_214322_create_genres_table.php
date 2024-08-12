<?php

declare(strict_types=1);

use App\Models\Genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Genre::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('tmdb_id')->unique();
            $table->string('name')->unique();
            $table->foreignIdFor(App\Models\User::class, 'user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Genre::getTableName());
    }
};
