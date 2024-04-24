<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight pl-10">
            {{ __('Exams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-6">
                <div class="p-4 pt-1 text-gray-900">
                    <div class="container mt-3">
                        <form action="{{ route('ExamUploadController') }}" method="post" enctype="multipart/form-data">
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
                            <div class="custom-file">
                                <label class="custom-file-label" for="chooseFile">Select exam:</label>
                                <input type="file" name="file" class="custom-file-input" id="chooseFile">
                                <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Upload Exam
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if(count($userFiles)!=0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                    <div class="p-6 pt-1 text-gray-900">
                        <div class="container mt-5">
                            <h3 class="p-2 pb-6">Your Files:</h3>
                            <ul>
                                @foreach($userFiles as $file)
                                    <li class="p-6 pt-2">
                                        <p>{{ $file->name }}
                                            <a href="{{ route('remove.file', ['id' => $file->id]) }}" role="button" class="btn btn-danger m-3 float-right">Remove</a>
                                            <a href="{{ route('rename.file', ['id' => $file->id]) }}" role="button" class="btn btn-primary m-3 float-right">Rename</a>
                                            <a href="{{ route('download.file', ['id' => $file->id]) }}" class="btn btn-success m-3 float-right">Download</a>
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>