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
        // Drop the problematic foreign key constraint
        DB::statement('ALTER TABLE tbl_vote_details DROP FOREIGN KEY tbl_vote_details_ibfk_2');

        // Recreate the foreign key constraint properly
        Schema::table('tbl_vote_details', function (Blueprint $table) {
            $table->foreign('candidate_id')
                ->references('candidate_id')
                ->on('tbl_candidates')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the recreated constraint
        Schema::table('tbl_vote_details', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
        });

        // Recreate the original constraint (for rollback)
        DB::statement('ALTER TABLE tbl_vote_details ADD CONSTRAINT tbl_vote_details_ibfk_2 FOREIGN KEY (candidate_id) REFERENCES tbl_candidates (candidate_id) ON DELETE CASCADE');
    }
};
