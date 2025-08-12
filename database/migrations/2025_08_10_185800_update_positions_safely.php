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
        // Update club_membership position field
        Schema::table('tbl_club_membership', function (Blueprint $table) {
            $table->text('club_position')->nullable()->change();
        });

        // For candidates table, we need to be more careful due to foreign key constraints
        // Let's add a new column and then migrate data
        Schema::table('tbl_candidates', function (Blueprint $table) {
            $table->text('position_new')->after('position');
        });

        // Copy data from old to new column
        DB::statement('UPDATE tbl_candidates SET position_new = position');

        // Drop the old column and rename the new one
        Schema::table('tbl_candidates', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('tbl_candidates', function (Blueprint $table) {
            $table->renameColumn('position_new', 'position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process
        Schema::table('tbl_candidates', function (Blueprint $table) {
            $table->string('position_new', 255)->after('position');
        });

        DB::statement('UPDATE tbl_candidates SET position_new = position');

        Schema::table('tbl_candidates', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('tbl_candidates', function (Blueprint $table) {
            $table->renameColumn('position_new', 'position');
        });

        Schema::table('tbl_club_membership', function (Blueprint $table) {
            $table->string('club_position', 255)->nullable()->change();
        });
    }
};
