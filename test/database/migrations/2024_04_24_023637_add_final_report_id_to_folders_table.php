<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinalReportIdToFoldersTable extends Migration
{
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->unsignedBigInteger('final_report_id')->nullable();
            $table->foreign('final_report_id')->references('id')->on('media')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('final_report_id');
        });
    }
}
