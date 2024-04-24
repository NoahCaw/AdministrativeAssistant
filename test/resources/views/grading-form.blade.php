<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grading Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('process.grading.form') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="exam_file" class="block text-gray-700 text-sm font-bold mb-2">Select Exam File:</label>
                            <input list="exam_files" name="exam_file" id="exam_file_input" class="form-input rounded-md shadow-sm mt-1 block w-full" required>

                            <!-- Datalist for exam files -->
                            <datalist id="exam_files">
                                @foreach($examFiles as $file)
                                    <option value="{{ $file->name }}" data-file-id="{{ $file->id }}">{{ $file->name }}</option>
                                @endforeach
                            </datalist>

                            <label for="report_file" class="block text-gray-700 text-sm font-bold mt-4 mb-2">Select Report File:</label>
                            <input list="report_files" name="report_file" id="report_file_input" class="form-input rounded-md shadow-sm mt-1 block w-full" required>

                            <!-- Datalist for report files -->
                            <datalist id="report_files">
                                @foreach($reportFiles as $file)
                                    <option value="{{ $file->name }}" data-file-id="{{ $file->id }}">{{ $file->name }}</option>
                                @endforeach
                            </datalist>

                            <label for="folder_name" class="block text-gray-700 text-sm font-bold mt-4 mb-2">Folder Name:</label>
                            <input type="text" name="folder_name" id="folder_name" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

