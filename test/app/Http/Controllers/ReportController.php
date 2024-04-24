<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FolderFile;
use App\Models\FileUser;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function showReportFiles()
    {
        $user = Auth::user();
    
        $reports = $user->ownedMedia()
                        ->where('collection_name', 'reports')
                        ->get();
    
        return view('reports', ['reports' => $reports]);
    }

    public function showCreateReportForm()
    {
        return view('create-report');
    }

    public function createReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sections' => 'required|integer|min:1',
        ]);

        session(['report_title' => $request->title]);
        session(['report_sections' => $request->sections]);

        return redirect()->route('dynamic.form');
    }

    public function showDynamicForm()
    {
        $title = session('report_title');
        $sections = session('report_sections');

        return view('dynamic-form', compact('title', 'sections'));
    }
    
    public function processDynamicForm(Request $request)
    {
        $user = Auth::user();

        $title = session('report_title');

        $sectionData = [];

        for ($i = 1; $i <= session('report_sections'); $i++) {
            $request->validate([
                "section{$i}" => 'required|string|max:255',
            ]);

            $sectionData["section{$i}"] = [
                'title' => $request->input("section{$i}"),
                'comments' => '',
                'mark' => ''
            ];
        }

        $finalData = [
            'title' => "Final",
            'comments' => '',
            'mark' => ''
        ];

        $reportData = [
            'title' => $title,
            'sections' => $sectionData,
            'final' => $finalData
        ];

        $jsonData = json_encode($reportData, JSON_PRETTY_PRINT);

        $fileName = $title . '_' . time() . '.json';

        $filePath = 'reports/' . $fileName;

        Storage::disk('local')->put($filePath, $jsonData);

        $report = $user->addMedia(Storage::path($filePath))
              ->usingName($title)
              ->toMediaCollection('reports');

        $fileId = $report->id;

        $fileUser = new FileUser();
        $fileUser->user_id = $user->id;
        $fileUser->role = 'owner'; 
        $fileUser->file_id = $fileId;
        $fileUser->save();

        return redirect()->route('reports')->with('success', 'Report submitted successfully.');
    }    


    public function showReportForm($id)
    {
        $user = Auth::user();
        
        $folderFile = FolderFile::where('id', $id)->firstOrFail();
        $fileId = $folderFile->file_id;
    
        $file = Media::findOrFail($fileId);
    
        $path = $file->getPath();
        $path = str_replace('/var/www/html/storage/app', '', $path);
    
        $content = Storage::get($path);
        $json = json_decode($content, true);
    
        return view('report-form', ['report' => $json]);
    }
    

    public function showUserReportForm($id)
    {
        $report = Media::findOrFail($id);

        $path = $report->getPath();
        $path = str_replace('/var/www/html/storage/app', '', $path);

        $content = Storage::get($path);
        $json = json_decode($content, true);


        return view('user-report-form', ['report' => $json]);
    }
    
}