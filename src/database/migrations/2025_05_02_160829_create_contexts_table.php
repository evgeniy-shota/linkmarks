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
        Schema::create('contexts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 150)->nullable();
            $table->boolean('is_root')->default(false);
            $table->integer('parent_context_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('order')->nullable();

            $table->timestamps();
        });

        Schema::table('contexts', function (Blueprint $table) {
            $table->index('user_id');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contexts');
    }
};
