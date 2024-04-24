<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_file_id')->nullable();
            $table->unsignedBigInteger('report_file_id')->nullable();

            $table->foreign('exam_file_id')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('report_file_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropForeign(['exam_file_id']);
            $table->dropForeign(['report_file_id']);
            $table->dropColumn(['exam_file_id', 'report_file_id']);
        });
    }
};
