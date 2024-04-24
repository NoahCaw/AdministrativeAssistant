<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUserTable extends Migration
{
    public function up()
    {
        Schema::create('file_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('file_id')->constrained('media')->onDelete('cascade');
            $table->timestamps();

            // Make (user_id, file_id) unique together
            $table->unique(['user_id', 'file_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_user');
    }
}
