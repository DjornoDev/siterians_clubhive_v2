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
        Schema::create('tbl_elections', function (Blueprint $table) {
            $table->id('election_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
        });

        Schema::create('tbl_candidates', function (Blueprint $table) {
            $table->id('candidate_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('user_id');
            $table->string('position');
            $table->timestamps();

            $table->foreign('election_id')
                ->references('election_id')
                ->on('tbl_elections')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');

            $table->unique(['election_id', 'user_id', 'position']);
        });

        Schema::create('tbl_votes', function (Blueprint $table) {
            $table->id('vote_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('voter_id');
            $table->timestamps();

            $table->foreign('election_id')
                ->references('election_id')
                ->on('tbl_elections')
                ->onDelete('cascade');

            $table->foreign('candidate_id')
                ->references('candidate_id')
                ->on('tbl_candidates')
                ->onDelete('cascade');

            $table->foreign('voter_id')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');

            $table->unique(['election_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_votes');
        Schema::dropIfExists('tbl_candidates');
        Schema::dropIfExists('tbl_elections');
    }
};
