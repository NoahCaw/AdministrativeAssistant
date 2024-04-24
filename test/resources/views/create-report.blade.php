<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('store.report') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                            <input type="text" name="title" id="title" class="form-input rounded-md shadow-sm mt-1 block w-full" required />


                            <label for="sections" class="block text-gray-700 text-sm font-bold mb-2">Number of Sections:</label>
                            <input type="number" name="sections" id="sections" class="form-input rounded-md shadow-sm mt-1 block w-full" required />
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Continue Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
