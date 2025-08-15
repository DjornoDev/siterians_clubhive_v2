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
        Schema::create('club_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id');
            $table->string('question');
            $table->enum('question_type', ['text', 'textarea', 'select', 'radio']);
            $table->json('options')->nullable(); // For select/radio options
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('club_id')
                ->references('club_id')
                ->on('tbl_clubs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_questions');
    }
};
