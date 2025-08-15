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
        Schema::table('tbl_posts', function (Blueprint $table) {
            $table->string('file_attachment')->nullable()->after('post_caption');
            $table->string('file_original_name')->nullable()->after('file_attachment');
            $table->string('file_mime_type')->nullable()->after('file_original_name');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_posts', function (Blueprint $table) {
            $table->dropColumn(['file_attachment', 'file_original_name', 'file_mime_type', 'file_size']);
        });
    }
};
