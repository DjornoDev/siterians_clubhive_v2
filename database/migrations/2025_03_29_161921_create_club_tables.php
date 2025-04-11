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
        Schema::create('tbl_clubs', function (Blueprint $table) {
            $table->id('club_id');
            $table->string('club_name');
            $table->unsignedBigInteger('club_adviser');
            $table->text('club_description')->nullable();
            $table->string('club_logo');
            $table->string('club_banner');
            $table->timestamps();

            $table->foreign('club_adviser')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');
        });

        Schema::create('tbl_club_membership', function (Blueprint $table) {
            $table->id('membership_id');
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('club_role', ['ADVISER', 'MEMBER']);
            $table->string('club_position')->nullable();
            $table->dateTime('joined_date');
            $table->json('club_accessibility')->nullable();
            $table->timestamps();

            $table->foreign('club_id')
                ->references('club_id')
                ->on('tbl_clubs')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');

            $table->unique(['club_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_club_membership');
        Schema::dropIfExists('tbl_clubs');
    }
};
