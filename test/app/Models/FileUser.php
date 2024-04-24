<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUser extends Model
{
    protected $table = 'file_user';

    protected $fillable = ['user_id', 'file_id', 'role'];

}
