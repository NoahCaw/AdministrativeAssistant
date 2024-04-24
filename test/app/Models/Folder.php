<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Folder extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'user_id', 'exam_file_id', 'report_file_id', 'origin_folder_id'];

    public function user()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function examFile()
    {
        return $this->hasOne(Media::class, 'id', 'exam_file_id');
    }

    public function reportFile()
    {
        return $this->hasOne(Media::class, 'id', 'report_file_id');
    }

    public function files()
    {
        return $this->hasMany(Media::class)->where('folder_id', $this->id);
    }

        public function folderFiles()
    {
        return $this->hasMany(FolderFile::class, 'folder_id');
    }

    public function finalReportFile()
    {
        return $this->hasOne(Media::class, 'id', 'final_report_id');
    }
}
