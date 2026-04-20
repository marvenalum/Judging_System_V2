<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds 'pending' to the submissions.status enum and sets it as the default.
     *
     * The original create_submissions_table migration already defines the status
     * column as ENUM('pending', 'draft', 'submitted', 'under_review', 'reviewed')
     * DEFAULT 'pending', so on a fresh install this migration is a no-op.
     *
     * We use Laravel's schema builder (->change()) instead of raw ALTER TABLE
     * statements so the migration works with both MySQL and SQLite.
     */
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'draft', 'submitted', 'under_review', 'reviewed'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'under_review', 'reviewed'])
                  ->default('draft')
                  ->change();
        });
    }
};
