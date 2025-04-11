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
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->enum('role', ['ADMIN', 'TEACHER', 'STUDENT']);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->rememberToken();
            $table->string('profile_picture')->nullable();
            $table->timestamps();

            // Explicit foreign key definition
            $table->foreign('section_id')
                ->references('section_id')
                ->on('tbl_sections')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
