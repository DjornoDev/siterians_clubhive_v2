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
        // Update club_membership table (this one is usually safe)
        Schema::table('tbl_club_membership', function (Blueprint $table) {
            $table->text('club_position')->nullable()->change();
        });

        // For candidates table, we need to handle foreign keys carefully
        // First, disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            Schema::table('tbl_candidates', function (Blueprint $table) {
                $table->text('position')->change();
            });
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_club_membership', function (Blueprint $table) {
            $table->string('club_position', 255)->nullable()->change();
        });

        // Disable foreign key checks for rollback too
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            Schema::table('tbl_candidates', function (Blueprint $table) {
                $table->string('position', 255)->change();
            });
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
};
