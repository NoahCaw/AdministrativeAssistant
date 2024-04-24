<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Folder;
use App\Models\FolderFile;
use App\Models\FileUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GradingController extends Controller
{
    public function showFolders()
    {
        $user = Auth::user();
        
        $folders = $user->folders()->get();

        return view('grading', compact('folders'));
    }

    public function showCreateFolder()
    {
        $user = Auth::user();
        $examFiles = Media::where('user_id', $user->id)
            ->where('collection_name', 'exams')
            ->get();
        $reportFiles = Media::where('user_id', $user->id)
            ->where('collection_name', 'reports')
            ->get();

        return view('grading-form', compact('examFiles', 'reportFiles'));
    }

    public function processGradingForm(Request $request)
    {      
        $request->validate([
            'exam_file' => 'required|string', 
            'report_file' => 'required|string',
            'folder_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $examFile = Media::where('name', $request->exam_file)
            ->where('collection_name', 'exams')
            ->first();

        if (!$examFile) {
            return back()->with('error', 'Exam file not found.');
        }

        $reportFile = Media::where('name', $request->report_file)
            ->where('collection_name', 'reports')
            ->first();

        if (!$reportFile) {
            return back()->with('error', 'Report file not found.');
        }

        $folder = Folder::create([
            'name' => $request->folder_name,
        ]);

        $folderFileExam = FolderFile::create([
            'folder_id' => $folder->id,
            'file_id' => $examFile->id,
        ]);

        $folderFileReport = FolderFile::create([
            'folder_id' => $folder->id,
            'file_id' => $reportFile->id,
        ]);

        $reportFile = Media::where('name', $request->report_file)
        ->where('collection_name', 'reports')
        ->first();

        $folder = Folder::create([
            'name' => $request->folder_name,
            'exam_file_id' => $examFile->id,
            'report_file_id' => $reportFile->id,
        ]);

        $folder->user()->attach($user, ['role' => 'owner']);

        return redirect()->route('grading')->with('success', 'Folder created successfully.');
    }


    public function showFolder($id)
    {
        $user = Auth::user();
        
        $folder = $user->folders()->findOrFail($id);
        
        $examFiles = Media::where('user_id', $user->id)
            ->where('collection_name', 'exams')
            ->get();
        
        $reportFiles = Media::where('user_id', $user->id)
            ->where('collection_name', 'reports')
            ->get();

        $finalReportFiles = Media::where('user_id', $user->id)
            ->where('collection_name', 'final_reports')
            ->get();

        $folderFiles = FolderFile::where('folder_id', $folder->id)->get();

        $fileIds = $folderFiles->pluck('file_id');

        $files = Media::whereIn('id', $fileIds)->get();

        $finalReportFile = $folder->finalReportFile;

        $fileOwnerships = [];

        foreach ($files as $file) {
            $fileOwnership = FileUser::where('file_id', $file->id)
                ->where('user_id', $user->id)
                ->first();
            $role = $fileOwnership ? $fileOwnership->role : null;
            $fileOwnerships[$file->id] = $role;
        }

        $users = User::all();

        return view('folder', compact('folder', 'examFiles', 'reportFiles', 'finalReportFile', 'users', 'files', 'fileOwnerships'));
    }

    public function showAddUserToFolderPage($folderId)
    {
        $folder = Folder::findOrFail($folderId);

        $users = User::whereNotExists(function ($query) use ($folderId) {
                $query->select(DB::raw(1))
                    ->from('folder_user')
                    ->whereColumn('users.id', 'folder_user.user_id')
                    ->where('folder_user.folder_id', $folderId);
            })
            ->where('id', '!=', Auth::id())
            ->get();

        return view('add-user', compact('folder', 'users'));
    }


    public function fillOutReport($folderId)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folderId);

        if (!$folder->reportFile) {
            return redirect()->back()->with('error', 'Report file does not exist in this folder.');
        }
        
        $path = $folder->reportFile->getPath();
        $path = str_replace('/var/www/html/storage/app', '', $path);

        $folder = Folder::find($folderId);

        $json = json_decode(Storage::get($path), true);

        return view('fill-report', ['report' => $json, 'folder' => $folder]);
    }

    public function editReport($folderId, $fileId)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folderId);
        
        $file = $user->media()->findOrFail($fileId);

        $path = $file->getPath();
        $path = str_replace('/var/www/html/storage/app', '', $path);

        $json = json_decode(Storage::get($path), true);

        return view('edit-report', ['report' => $json,'folder' => $folder, 'file' => $file]);
    }

    public function destroyFolder($id)
    {
        $folder = Folder::findOrFail($id);
        $user = Auth::user();

        $folderUser = $user->folders()->where('folder_id', $id)->first();
        $role = $folderUser ? $folderUser->pivot->role : null;

        if ($role === 'owner') {
            $userFiles = $user->files()->where('folder_id', $id)->get();
            foreach ($userFiles as $file) {
                if ($folder->files->contains($file->id)) {
                    $file->delete();
                    $folder->files()->detach($file->id);
                }
            }

            $folder->folderFiles()->delete();

            $folder->delete();

            return redirect()->back()->with('success', 'Folder and associated files deleted successfully.');
        } else {
            $user->folders()->detach($id);

            return redirect()->back()->with('success', 'Your access to the folder has been revoked.');
        }
    }

    public function addUserToFolder(Request $request, $folderId)
    {
        $folder = Folder::findOrFail($folderId);
        
        $folder->user()->attach($request->user_id, ['role' => 'reader']);

        $folderFiles = FolderFile::where('folder_id', $folderId)->get();

        $fileIds = $folderFiles->pluck('file_id');

        $files = Media::whereIn('id', $fileIds)->get();

        foreach ($files as $file) {
            $existingEntry = FileUser::where('user_id', $request->user_id)
                ->where('file_id', $file->id)
                ->first();

            if (!$existingEntry) {
                FileUser::create([
                    'file_id' => $file->id,
                    'user_id' => $request->user_id,
                    'role' => 'reader',
                ]);
            }
        }

        return redirect()->back()->with('success', 'User added to folder as a reader successfully.');
    }

    public function processDynamicForm(Request $request)
    {
        $user = Auth::user();
        $folderId = $request->input('folder_id');
        $folder = $user->folders()->findOrFail($folderId);
        $originalReportFile = $folder->reportFile;

        if (!$originalReportFile) {
            return redirect()->back()->with('error', 'Original report file not found.');
        }

        $originalFilePath = $originalReportFile->getPath();
        $originalFilePath = str_replace('/var/www/html/storage/app', '', $originalFilePath);

        $originalJson = json_decode(Storage::get($originalFilePath), true);

        $modifiedJson = $originalJson;

        foreach ($request->input('sections') as $sectionNumber => $sectionData) {
            $modifiedJson['sections'][$sectionNumber]['comments'] = $sectionData['comments'];
            $modifiedJson['sections'][$sectionNumber]['mark'] = $sectionData['mark'];
        }

        $modifiedJson['final']['comments'] = $request->input('final.comments');
        $modifiedJson['final']['mark'] = $request->input('final.mark');
        $modifiedJsonData = json_encode($modifiedJson, JSON_PRETTY_PRINT);

        $newFileName = $user->first_name . ' ' . $originalReportFile->name;

        $newFilePath = $newFileName . '.json';
        Storage::disk('local')->put($newFilePath, $modifiedJsonData);

        $newReportFile = $user->addMedia(Storage::path($newFilePath))
            ->usingName($newFileName)
            ->toMediaCollection('filled_reports');

        FolderFile::create([
            'folder_id' => $folderId,
            'file_id' => $newReportFile->id,
        ]);

        $folderUsers = $folder->user;
        foreach ($folderUsers as $folderUser) {
            if ($folderUser->id === $user->id) {
                $role = 'owner';
            } else {
                $role = 'reader';
            }
        
            FileUser::create([
                'user_id' => $folderUser->id,
                'file_id' => $newReportFile->id,
                'role' => $role,
            ]);
        }        

        return redirect('grading')->with('success', 'Report submitted successfully.');
    }

    public function processEditForm(Request $request)
    {
        $user = Auth::user();
        $folderId = $request->input('folder_id');
        $folder = $user->folders()->findOrFail($folderId);
        $fileId = $request->input('file_id');
        $originalReportFile = $folder->report_file_id;

        $media = Media::findOrFail($originalReportFile);
        $originalFilePath = $media->getPath();
        $originalFilePath = str_replace('/var/www/html/storage/app', '', $originalFilePath);

        $originalJson = json_decode(Storage::get($originalFilePath), true);

        $modifiedJson = $originalJson;

        foreach ($request->input('sections') as $sectionNumber => $sectionData) {
            $modifiedJson['sections'][$sectionNumber]['comments'] = $sectionData['comments'];
            $modifiedJson['sections'][$sectionNumber]['mark'] = $sectionData['mark'];
        }
        $modifiedJson['final']['comments'] = $request->input('final.comments');
        $modifiedJson['final']['mark'] = $request->input('final.mark');
        
        $modifiedJsonData = json_encode($modifiedJson, JSON_PRETTY_PRINT);

        Storage::disk('local')->put($originalFilePath, $modifiedJsonData);

        $newReportFile = $user->addMedia(Storage::path($originalFilePath))
            ->usingName($media->name)
            ->toMediaCollection('filled_reports');

        Storage::delete($originalFilePath);

        $media->delete();
        FolderFile::where('file_id', $originalReportFile)->delete();
        FileUser::where('file_id', $originalReportFile)->delete();

        FolderFile::create([
            'folder_id' => $folderId,
            'file_id' => $newReportFile->id,
        ]);

        $folderUsers = $folder->user;
        foreach ($folderUsers as $folderUser) {
            if ($folderUser->id === $user->id) {
                $role = 'owner';
            } else {
                $role = 'reader';
            }
        
            FileUser::create([
                'user_id' => $folderUser->id,
                'file_id' => $newReportFile->id,
                'role' => $role,
            ]);
        } 

        return redirect('grading')->with('success', 'Report updated successfully.');
    }

    public function showFolderDetails($id)
    {
        $folder = Folder::findOrFail($id);

        $folder->load('folderFiles.file');

        return view('folder-details', compact('folder'));
    }

    public function createFinalReport(Request $request, $folderId)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folderId);

        if (!$folder->reportFile) {
            return redirect()->back()->with('error', 'Report file does not exist in this folder.');
        }
        
        $path = $folder->reportFile->getPath();
        $path = str_replace('/var/www/html/storage/app', '', $path);

        $folder = Folder::find($folderId);

        $json = json_decode(Storage::get($path), true);

        return view('create-final-report', ['report' => $json, 'folder' => $folder]);
    }

    public function processFinalForm(Request $request, $id)
    {
        $user = Auth::user();
        $folderId = $request->input('folder_id');
        $folder = $user->folders()->findOrFail($folderId);
        $originalReportFile = $folder->reportFile;

        if (!$originalReportFile) {
            return redirect()->back()->with('error', 'Original report file not found.');
        }

        $originalFilePath = $originalReportFile->getPath();
        $originalFilePath = str_replace('/var/www/html/storage/app', '', $originalFilePath);

        $originalJson = json_decode(Storage::get($originalFilePath), true);

        $modifiedJson = $originalJson;

        foreach ($request->input('sections') as $sectionNumber => $sectionData) {
            $modifiedJson['sections'][$sectionNumber]['comments'] = $sectionData['comments'];
            $modifiedJson['sections'][$sectionNumber]['mark'] = $sectionData['mark'];
        }

        $modifiedJson['final']['comments'] = $request->input('final.comments');
        $modifiedJson['final']['mark'] = $request->input('final.mark');
        $modifiedJsonData = json_encode($modifiedJson, JSON_PRETTY_PRINT);

        $newFileName = $user->first_name . ' ' . $originalReportFile->name;

        $newFilePath = $folder->name . "_final_report"  . ".json";
        Storage::disk('local')->put($newFilePath, $modifiedJsonData);

        $newReportFile = $user->addMedia(Storage::path($newFilePath))
            ->usingName($folder->name . " Final Report")
            ->toMediaCollection('final_reports');

        $folderFileReport = FolderFile::create([
            'folder_id' => $folder->id,
            'file_id' => $newReportFile->id,
        ]);

        $folder->final_report_id = $newReportFile->id;
        $folder->save();

        return redirect('grading')->with('success', 'Report submitted successfully.');
    }

    public function processEditFinalForm(Request $request)
    {
        $user = Auth::user();
        $folderId = $request->input('folder_id');
        $folder = $user->folders()->findOrFail($folderId);
        $fileId = $request->input('file_id');
        $originalReportFile = $folder->final_report_id;

        $media = Media::findOrFail($originalReportFile);
        $originalFilePath = $media->getPath();
        $originalFilePath = str_replace('/var/www/html/storage/app', '', $originalFilePath);

        $originalJson = json_decode(Storage::get($originalFilePath), true);

        $modifiedJson = $originalJson;

        $modifiedJson['final']['comments'] = $request->input('final.comments');
        $modifiedJson['final']['mark'] = $request->input('final.mark');

        $modifiedJsonData = json_encode($modifiedJson, JSON_PRETTY_PRINT);

        Storage::disk('local')->put($originalFilePath, $modifiedJsonData);

        return redirect('grading')->with('success', 'Report updated successfully.');
    }

    public function editFinalForm($folderId, $fileId)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folderId);
        
        $file = $user->media()->findOrFail($fileId);

        $path = $file->getPath();
        $path = str_replace('/var/www/html/storage/app', '', $path);

        $json = json_decode(Storage::get($path), true);

        return view('edit-final-form', ['report' => $json,'folder' => $folder, 'file' => $file]);
    }
}
