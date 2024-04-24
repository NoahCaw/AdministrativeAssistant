<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $report['title'] }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach($report['sections'] as $sectionNumber => $sectionContent)
                        <h3 class="font-semibold text-md text-gray-800 pt-3">{{ $sectionContent['title'] }}:</h3>
                        <div class="p-6 bg-white border-b border-gray-200">
                            <label>Comments:</label>
                            <textarea class="mb-4 form-input rounded-md shadow-sm mt-1 block w-full" readonly>{{ $sectionContent['comments'] }}</textarea>
                            <label>Mark:</label>
                            <input type="number" class="form-input rounded-md shadow-sm mt-1 block w-full" value={{ $sectionContent['mark'] }} readonly>
                        </div>
                    @endforeach
                    <h3 class="font-semibold text-md text-gray-800 pt-3">{{ $report['final']['title'] }}:</h3>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <label>Comments:</label>
                        <textarea class="mb-4 form-input rounded-md shadow-sm mt-1 block w-full" readonly></textarea>
                        <label>Mark:</label>
                        <input type="number" class="form-input rounded-md shadow-sm mt-1 block w-full" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
