<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $folder->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(!$finalReportFile)
                        @if($folder->user()->where('user_id', Auth::id())->first()->pivot->role === 'owner')
                            <a href="{{ route('create-final-report', ['id' => $folder->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Final Report</a>
                        @endif
                    @endif
                    <a href="{{ route('add_user_to_folder_page', ['folder_id' => $folder->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add User</a>
                </div>
            </div>

            <!-- Display Exam and Report Files -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($finalReportFile)
                        <h2>Final Report File: <a href="{{ route('edit-final-form', ['folderId' => $folder->id, 'fileId' => $finalReportFile->id]) }}">{{ $finalReportFile->name }} </a></h2> 
                    @else
                        <h2>No Final Report File</h2>
                    @endif
                    @if($folder->examFile)
                        <h2>Exam File: <a href="{{ route('download.file', ['id' => $folder->examFile->id]) }}">{{ $folder->examFile->name }}</a></h2>
                    @else
                        <h2>No Exam File</h2>
                    @endif
                    @if($folder->reportFile)
                        <h2 class="mb-5">Report File: <a href="{{ route('view.user.report.form', ['id' => $folder->reportFile->id]) }}">{{ $folder->reportFile->name }}</a></h2>
                        <a href="{{ route('fill_report', ['id' => $folder->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Fill out Report</a>
                    @else
                        <h2>No Report File</h2>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(isset($folder->folderFiles) && $folder->folderFiles->isNotEmpty())
                            <h2>Files:</h2>
                            <ul>
                                @foreach($folder->folderFiles as $folderFile)
                                    <li>
                                        @if($role = \App\Models\FileUser::where('file_id', $folderFile->file_id)
                                                ->where('user_id', auth()->id())
                                                ->value('role'))
                                            @if($role === 'owner')
                                            <a href="{{ route('edit_report', ['folderId' => $folder->id, 'fileId' => $folderFile->file_id]) }}">{{ $folderFile->file->name }}</a>
                                            <form action="{{ route('remove.file', ['id' => $folderFile->file_id]) }}" method="POST" class="float-right mr-10 mb-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            @else
                                            <a href="{{ route('view.user.report.form', ['id' => $folderFile->file_id]) }}">{{ $folderFile->file->name }}</a>
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                        <h2>No files in this folder.</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
