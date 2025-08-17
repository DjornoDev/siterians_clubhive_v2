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
        Schema::create('tbl_event_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->unsignedBigInteger('event_id');
            $table->string('document_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->bigInteger('file_size')->unsigned();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->foreign('event_id')->references('event_id')->on('tbl_events')->onDelete('cascade');
            $table->index(['event_id', 'uploaded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_event_documents');
    }
};
