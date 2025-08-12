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
        Schema::create('tbl_club_join_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->unique(['club_id', 'user_id']); // Prevent duplicate requests
            $table->foreign('club_id')->references('club_id')->on('tbl_clubs')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('tbl_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_club_join_requests');
    }
};
