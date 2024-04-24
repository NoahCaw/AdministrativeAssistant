<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Models\Folder;
use App\Models\FileUser;
use App\Models\Meeting;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('exams');
    }

    public function folders()
    {
        return $this->belongsToMany(Folder::class)->withPivot('role');
    }

    public function hasFolderAccess($folderId)
    {
        return $this->folders()->where('folder_id', $folderId)->exists();
    }

    public function ownedMedia()
    {
        return $this->belongsToMany(Media::class, 'file_user', 'user_id', 'file_id')
                    ->wherePivot('role', 'owner');
    }

    public function files()
    {
        return $this->hasMany(FileUser::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_user', 'user_id', 'meeting_id')
                    ->withPivot('status');
    }
}

