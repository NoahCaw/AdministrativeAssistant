<!-- dynamic-form.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('More Details for Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>More Details for Report:</p>
                    <p>{{ $title }}</p>
                    <!-- Form for dynamic fields -->
                    <form method="POST" action="{{ route('dynamic.form.submit') }}">
                        @csrf

                        <!-- Add dynamic fields based on the number of sections -->
                        @for ($i = 1; $i <= $sections; $i++)
                            <div class="mb-4">
                                <label for="section{{ $i }}" class="block text-gray-700 text-sm font-bold mb-2">Section {{ $i }} Heading:</label>
                                <input type="text" name="section{{ $i }}" id="section{{ $i }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required />
                            </div>
                        @endfor

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit Dynamic Form</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
