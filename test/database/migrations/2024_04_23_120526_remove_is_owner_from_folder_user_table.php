<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsOwnerFromFolderUserTable extends Migration
{
    public function up()
    {
        Schema::table('folder_user', function (Blueprint $table) {
            $table->dropColumn('is_owner');
        });
    }

    public function down()
    {
        Schema::table('folder_user', function (Blueprint $table) {
            $table->boolean('is_owner')->default(false)->after('role');
        });
    }
}
