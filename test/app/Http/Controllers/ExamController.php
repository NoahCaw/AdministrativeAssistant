<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\File;
use App\Models\FileUser;

class ExamController extends Controller
{
    public function ExamUploadController(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:txt,pdf,docx,png,jpeg,jpg|max:2048'
        ]);
    
        $user = Auth::user();
    
        $mediaCollection = 'exams';
    
        $file = $user->addMedia($req->file('file'))
                     ->toMediaCollection($mediaCollection);

        $fileUser = new FileUser();
        $fileUser->user_id = $user->id;
        $fileUser->file_id = $file->id;
        $fileUser->role = 'owner'; 
        $fileUser->save();
    
        return back()->with('success', 'File has been uploaded.');
    }

    public function showExamFiles()
    {                                                                                      
        $user = Auth::user();
    
        $examFiles = $user->ownedMedia()
                          ->where('collection_name', 'exams')
                          ->get();
    
        return view('exams', ['userFiles' => $examFiles]);
    }
     
}
