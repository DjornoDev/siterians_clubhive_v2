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
        Schema::create('tbl_events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name');
            $table->text('event_description')->nullable();
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('organizer_id');
            $table->dateTime('event_date');
            $table->string('event_location')->nullable();
            $table->timestamps();

            $table->foreign('club_id')
                ->references('club_id')
                ->on('tbl_clubs')
                ->onDelete('cascade');

            $table->foreign('organizer_id')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');
        });

        Schema::create('tbl_posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->text('post_caption');
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('author_id');
            $table->enum('post_visibility', ['PUBLIC', 'CLUB_ONLY'])->default('CLUB_ONLY');
            $table->dateTime('post_date');
            $table->timestamps();

            $table->foreign('club_id')
                ->references('club_id')
                ->on('tbl_clubs')
                ->onDelete('cascade');

            $table->foreign('author_id')
                ->references('user_id')
                ->on('tbl_users')
                ->onDelete('cascade');
        });

        Schema::create('tbl_post_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->unsignedBigInteger('post_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('post_id')
                ->references('post_id')
                ->on('tbl_posts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_post_images');
        Schema::dropIfExists('tbl_posts');
        Schema::dropIfExists('tbl_events');
    }
};
