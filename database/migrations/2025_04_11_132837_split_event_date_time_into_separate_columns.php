<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_events', function (Blueprint $table) {
            $table->renameColumn('event_date', 'event_datetime');
        });

        Schema::table('tbl_events', function (Blueprint $table) {
            $table->date('event_date')->after('event_datetime');
            $table->string('event_time')->nullable()->after('event_date');
        });

        DB::statement('UPDATE tbl_events SET event_date = DATE(event_datetime), event_time = DATE_FORMAT(event_datetime, "%h:%i %p")');

        Schema::table('tbl_events', function (Blueprint $table) {
            $table->dropColumn('event_datetime');
        });
    }

    public function down()
    {
        Schema::table('tbl_events', function (Blueprint $table) {
            $table->dateTime('event_datetime');
        });

        DB::statement('UPDATE tbl_events SET event_datetime = CONCAT(event_date, " ", STR_TO_DATE(event_time, "%h:%i %p"))');

        Schema::table('tbl_events', function (Blueprint $table) {
            $table->dropColumn(['event_date', 'event_time']);
            $table->renameColumn('event_datetime', 'event_date');
        });
    }
};
