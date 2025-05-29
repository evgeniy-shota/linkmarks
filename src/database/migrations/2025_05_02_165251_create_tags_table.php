<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('short_name', 8)->nullable();
            $table->string('description', 400)->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->json('style')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
