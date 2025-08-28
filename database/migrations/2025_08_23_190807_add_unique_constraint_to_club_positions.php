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
        Schema::table('tbl_club_membership', function (Blueprint $table) {
            // Add unique constraint: club_id + club_position combination must be unique
            // This ensures only one member per club can hold each position
            $table->unique(['club_id', 'club_position'], 'unique_club_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_club_membership', function (Blueprint $table) {
            // Remove the unique constraint
            $table->dropUnique('unique_club_position');
        });
    }
};
