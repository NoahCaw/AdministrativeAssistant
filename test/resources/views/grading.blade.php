<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight pl-10">
            {{ __('Grading Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-6">
                <div class="p-6 pt-1 text-gray-900">
                    <div class="container mt-5">
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
                        <a href="{{ route('grading.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2">Create New Folder</a>
                    </div>
                </div>
            </div>
            @if(count($folders) != 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                <div class="p-6 pt-1 text-gray-900">
                    <div class="container mt-5">
                        <h3 class="p-2 pb-6">Your Folders:</h3>
                        <ul>
                            @foreach($folders as $folder)
                                <li class="p-6 pt-2">
                                    <a href="{{ route('grading.showFolderDetails', $folder->id) }}" class="btn btn-success">{{ $folder->name }}</a>
                                    <form action="{{ route('grading.destroyFolder', $folder->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mr-5 mb-10 float-right">Delete</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
