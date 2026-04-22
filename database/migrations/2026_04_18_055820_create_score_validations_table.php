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
        Schema::create('score_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->constrained('scores')->onDelete('cascade');
            $table->string('validation_type'); // 'range', 'consistency', 'rubric'
            $table->boolean('is_valid');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_validations');
    }
};
