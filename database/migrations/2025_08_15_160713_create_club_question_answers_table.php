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
        Schema::create('club_question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_join_request_id');
            $table->unsignedBigInteger('club_question_id');
            $table->unsignedBigInteger('user_id');
            $table->text('answer');
            $table->timestamps();

            $table->foreign('club_join_request_id')
                ->references('request_id')
                ->on('tbl_club_join_requests')
                ->onDelete('cascade');

            $table->foreign('club_question_id')
                ->references('id')
                ->on('club_questions')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_question_answers');
    }
};
