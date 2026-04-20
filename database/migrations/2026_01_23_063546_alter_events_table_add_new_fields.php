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
        // Rename columns first, each in its own Schema::table call so that
        // subsequent hasColumn checks reflect the updated column names.
        if (Schema::hasColumn('events', 'name')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('name', 'event_name');
            });
        }

        if (Schema::hasColumn('events', 'description')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('description', 'event_description');
            });
        }

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->enum('event_type', ['pageant', 'contest', 'competition'])->after('event_description');
            }
            if (Schema::hasColumn('events', 'status')) {
                $table->dropColumn('status');
            }
            if (!Schema::hasColumn('events', 'event_status')) {
                $table->enum('event_status', ['upcoming', 'ongoing', 'completed'])->default('upcoming')->after('event_type');
            }
            if (!Schema::hasColumn('events', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('event_status');
                $table->foreign('created_by')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
