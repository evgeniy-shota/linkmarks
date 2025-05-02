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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('context_id')->constrained('contexts')->onDelete('cascade');
            $table->string('link', 400);
            $table->string('name', 150);
            $table->foreignId('thumbnail_id')->constrained('thumbnails');
            $table->boolean('is_enabled')->default(true);
            $table->integer('order');

            $table->timestamps();
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->index('context_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
