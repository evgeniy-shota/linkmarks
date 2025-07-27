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
        Schema::create('test_users', function (Blueprint $table) {
            $table->id();
            $table->tinyText('name', 32);
            $table->string('email', 64)->unique();
            $table->tinyText('password', 32);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_enabled')->default(false);
        });

        Schema::create('test_contexts', function (Blueprint $table) {
            $table->id()->unique();
            $table->tinyText('name', 64)->nullable();
            $table->boolean('is_root')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->string('tags')->nullable();
            $table->tinyInteger('order')->nullable();
        });

        Schema::create('test_bookmarks', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('link', 400);
            $table->string('name', 128);
            $table->boolean('is_enabled')->default(true);
            $table->tinyText('context')->nullable();
            $table->string('tags')->nullable();
            $table->tinyInteger('order')->nullable();
        });

        Schema::create('test_tags', function (Blueprint $table) {
            $table->id()->unique();
            $table->tinyText('name', 10);
            $table->boolean('is_enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_users');
        Schema::dropIfExists('test_bookmarks');
        Schema::dropIfExists('test_contexts');
        Schema::dropIfExists('test_tags');
    }
};
