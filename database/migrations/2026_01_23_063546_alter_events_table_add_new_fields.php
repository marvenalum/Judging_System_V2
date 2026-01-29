<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'name')) {
                DB::statement('ALTER TABLE events CHANGE name event_name VARCHAR(255)');
            }
            if (Schema::hasColumn('events', 'description')) {
                DB::statement('ALTER TABLE events CHANGE description event_description TEXT');
            }
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
