<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\File;
use App\Models\FileUser;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function showRenameForm($id)
    {
        $user = Auth::user();

        $file = $user->media()->find($id);

        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return view('rename', ['file' => $file]);
    }

    public function renameFile(Request $request, $id)
    {
        $user = Auth::user();

        $file = Media::find($id);

        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        $collection = $file->collection_name;

        $request->validate([
            'new_name' => 'required|min:3|max:255',
        ], [
            'new_name.required' => 'The new name field is required.',
            'new_name.min' => 'The new name must be at least three characters.',
        ]);

        $existingFile = $user->media()->where('name', $request->input('new_name'))->first();

        if ($existingFile) {
            return redirect()->back()->with('warning', 'A file with the same name already exists. Please choose a different name.');
        }

        $file->name = $request->input('new_name');
        $file->save();

        $redirectRoute = ($collection === 'exams') ? 'exams' : 'reports';

        return redirect()->route($redirectRoute)->with('success', 'File has been renamed.');
    }

    public function removeFile($id)
    {
        $user = Auth::user();

        $file = $user->media()->find($id);

        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }
        $file->delete();

        return redirect()->back()->with('success', 'File has been removed.');
    }

    public function downloadFile($id)
    {
        $media = Media::findOrFail($id);

        $filePath = $media->getPath();

        $fileContents = file_get_contents($filePath);

        return Response::make($fileContents, 200, [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => 'attachment; filename="'.$media->file_name.'"',
        ]);
    }
}
