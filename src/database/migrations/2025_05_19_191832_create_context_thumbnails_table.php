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
        Schema::create('contexts_thumbnails', function (Blueprint $table) {
            $table->id();

            $table->foreignId('context_id')->constrained('contexts')->onDelete('cascade');
            $table->foreignId('thumbnail_id')->constrained('thumbnails')->onDelete('cascade');
            $table->boolean('seted_by_user')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contexts_thumbnails');
    }
};
