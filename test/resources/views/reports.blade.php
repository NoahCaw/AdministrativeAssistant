<!-- reports.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight pl-10">
            {{ __('Reports') }}
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
                        <a href="{{ route('create.report') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2">Create New Report</a>
                    </div>
                </div>
            </div>
            @if(count($reports) != 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                    <div class="p-6 pt-1 text-gray-900">
                        <div class="container mt-5">
                            <h3 class="p-2 pb-6">Your Reports:</h3>
                            <ul>
                                @foreach($reports as $report)
                                    <li class="p-6 pt-2">
                                        <p>
                                            <a href="{{ route('view.user.report.form', ['id' => $report->id]) }}" role="button" class="btn btn-info">{{ $report->name }}</a>
                                            <a href="{{ route('remove.file', ['id' => $report->id]) }}" role="button" class="btn btn-danger m-3 float-right">Remove</a>
                                            <a href="{{ route('rename.file', ['id' => $report->id]) }}" role="button" class="btn btn-primary m-3 float-right">Rename</a>
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
