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
        Schema::create('context_styles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('context_id')->constrained('contexts')->onDelete('cascade');
            $table->string('border_style')->nullable();
            $table->string('border_color')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('context_styles');
    }
};
