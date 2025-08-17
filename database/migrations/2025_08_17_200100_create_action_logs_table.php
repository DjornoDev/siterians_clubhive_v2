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
        Schema::create('action_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable(); // Store user name for historical data
            $table->enum('user_role', ['ADMIN', 'TEACHER', 'STUDENT'])->nullable();
            $table->string('action_category'); // authentication, user_management, club_management, etc.
            $table->string('action_type'); // login, logout, created, updated, deleted, etc.
            $table->string('action_description'); // Detailed description of the action
            $table->json('action_details')->nullable(); // Store additional details as JSON
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Foreign key constraint to tbl_users
            $table->foreign('user_id')->references('user_id')->on('tbl_users')->onDelete('set null');

            // Index for better performance
            $table->index(['created_at', 'user_id']);
            $table->index(['action_category', 'action_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};
