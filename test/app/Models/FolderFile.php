<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FolderFile extends Model
{
    use HasFactory;

    protected $table = 'folder_file';

    protected $fillable = [
        'folder_id',
        'file_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function file()
    {
        return $this->belongsTo(Media::class, 'file_id');
    }
}
