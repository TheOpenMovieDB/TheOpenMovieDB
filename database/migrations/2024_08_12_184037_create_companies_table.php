<?php

declare(strict_types=1);

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Company::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->integer('tmdb_id');
            $table->foreignIdFor(\App\Models\User::class,'user_id');
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->string('origin_country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Company::getTableName());
    }
};
