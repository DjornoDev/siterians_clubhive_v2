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
        Schema::table('tbl_events', function (Blueprint $table) {
            $table->enum('event_visibility', ['PUBLIC', 'CLUB_ONLY'])
                ->default('CLUB_ONLY')
                ->after('event_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_events', function (Blueprint $table) {
            $table->dropColumn('event_visibility');
        });
    }
};
