<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOwnerToFolderUserTable extends Migration
{
    public function up()
    {
        Schema::table('folder_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('folder_id')->constrained()->onDelete('cascade');
            $table->boolean('is_owner')->default(false)->after('role');
        });
    }

    public function down()
    {
        Schema::table('folder_user', function (Blueprint $table) {
            $table->dropColumn('is_owner');
            $table->dropColumn('user_id');
            $table->dropColumn('folder_id');
        });
    }
}
