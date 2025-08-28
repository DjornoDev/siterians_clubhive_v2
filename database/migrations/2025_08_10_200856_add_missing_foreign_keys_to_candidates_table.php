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
        Schema::table('tbl_candidates', function (Blueprint $table) {
            // Check if foreign key constraints already exist before adding them
            $foreignKeys = $this->getForeignKeys('tbl_candidates');

            if (!in_array('tbl_candidates_election_id_foreign', $foreignKeys)) {
                $table->foreign('election_id')->references('election_id')->on('tbl_elections')->onDelete('cascade');
            }

            if (!in_array('tbl_candidates_user_id_foreign', $foreignKeys)) {
                $table->foreign('user_id')->references('user_id')->on('tbl_users')->onDelete('cascade');
            }
        });
    }

    /**
     * Get existing foreign key names for a table
     */
    private function getForeignKeys($tableName)
    {
        $foreignKeys = [];
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$tableName]);

        foreach ($constraints as $constraint) {
            $foreignKeys[] = $constraint->CONSTRAINT_NAME;
        }

        return $foreignKeys;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_candidates', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['election_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
