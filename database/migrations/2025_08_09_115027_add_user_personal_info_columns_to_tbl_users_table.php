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
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->enum('sex', ['MALE', 'FEMALE'])->nullable()->after('email');
            $table->text('address')->nullable()->after('sex');
            $table->string('contact_no', 20)->nullable()->after('address');
            $table->string('mother_name')->nullable()->after('section_id');
            $table->string('mother_contact_no', 20)->nullable()->after('mother_name');
            $table->string('father_name')->nullable()->after('mother_contact_no');
            $table->string('father_contact_no', 20)->nullable()->after('father_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->dropColumn([
                'sex',
                'address',
                'contact_no',
                'mother_name',
                'mother_contact_no',
                'father_name',
                'father_contact_no'
            ]);
        });
    }
};
