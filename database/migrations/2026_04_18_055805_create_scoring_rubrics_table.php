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
        Schema::create('scoring_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->integer('score_level'); // 1, 2, 3, 4, 5, etc.
            $table->string('level_name'); // 'Poor', 'Fair', 'Good', 'Excellent'
            $table->text('description'); // Detailed rubric text
            $table->timestamps();
            
            $table->unique(['criteria_id', 'score_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_rubrics');
    }
};
