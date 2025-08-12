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
        Schema::table('tbl_clubs', function (Blueprint $table) {
            $table->enum('category', ['academic', 'sports', 'service'])->default('academic')->after('club_description');
            $table->boolean('requires_approval')->default(true)->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_clubs', function (Blueprint $table) {
            $table->dropColumn(['category', 'requires_approval']);
        });
    }
};
