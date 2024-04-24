<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rename File') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 pt-3 text-gray-900">
                    <div class="container mt-5">
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('rename.file.submit', ['id' => $file->id]) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="new_name" class="block text-gray-700 text-sm font-bold mb-3">New File Name for {{ $file->name }}:</label>
                                <input type="text" name="new_name" id="new_name" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('new_name', $file->custom_name) }}" required>
                                @error('new_name')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Rename</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>