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
        Schema::table('tbl_candidates', function (Blueprint $table) {
            // Add missing foreign key constraints
            $table->foreign('election_id')->references('election_id')->on('tbl_elections')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('tbl_users')->onDelete('cascade');
        });
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
