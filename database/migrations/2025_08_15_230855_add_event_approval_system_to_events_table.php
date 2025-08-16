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
        Schema::table('tbl_events', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('event_visibility');
            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->string('supporting_documents')->nullable()->after('rejection_reason');
            $table->string('supporting_documents_original_name')->nullable()->after('supporting_documents');
            $table->string('supporting_documents_mime_type')->nullable()->after('supporting_documents_original_name');
            $table->bigInteger('supporting_documents_size')->unsigned()->nullable()->after('supporting_documents_mime_type');
            $table->timestamp('approved_at')->nullable()->after('supporting_documents_size');
            $table->bigInteger('approved_by')->unsigned()->nullable()->after('approved_at');

            $table->foreign('approved_by')->references('user_id')->on('tbl_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_events', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'supporting_documents',
                'supporting_documents_original_name',
                'supporting_documents_mime_type',
                'supporting_documents_size',
                'approved_at',
                'approved_by'
            ]);
        });
    }
};
