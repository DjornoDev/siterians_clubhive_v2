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
        Schema::create('tbl_classes', function (Blueprint $table) {
            $table->id('class_id');
            $table->unsignedTinyInteger('grade_level')->checkBetween([7, 12]);
            $table->timestamps();

            $table->unique('grade_level');
        });

        Schema::create('tbl_sections', function (Blueprint $table) {
            $table->id('section_id');
            $table->unsignedBigInteger('class_id'); // Match the class_id type
            $table->string('section_name', 50);
            $table->timestamps();

            $table->foreign('class_id')
                ->references('class_id')
                ->on('tbl_classes')
                ->onDelete('cascade');

            $table->unique(['class_id', 'section_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_sections');
        Schema::dropIfExists('tbl_classes');
    }
};
