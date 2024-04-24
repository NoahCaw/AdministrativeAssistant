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
                    <form action="{{ route('process_dynamic_form') }}" method="POST">
                        @csrf
                        <input type="hidden" name="report_title" value="{{ $report['title'] }}">
                        @if(isset($folder))
                        <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                        @endif
                        
                        @foreach($report['sections'] as $sectionNumber => $sectionContent)
                            <h3 class="font-semibold text-md text-gray-800 pt-3">{{ $sectionContent['title'] }}:</h3>
                            <div class="p-6 bg-white border-b border-gray-200">
                                <label>Comments:</label>
                                <textarea name="sections[{{ $sectionNumber }}][comments]" class="mb-4 form-input rounded-md shadow-sm mt-1 block w-full">{{ $sectionContent['comments'] }}</textarea>
                                <label>Mark:</label>
                                <input type="number" name="sections[{{ $sectionNumber }}][mark]" class="form-input rounded-md shadow-sm mt-1 block w-full" value={{ $sectionContent['mark'] }}>
                            </div>
                        @endforeach

                        <h3 class="font-semibold text-md text-gray-800 pt-3">{{ $report['final']['title'] }}:</h3>
                        <div class="p-6 bg-white border-b border-gray-200">
                            <label>Comments:</label>
                            <textarea name="final[comments]" class="mb-4 form-input rounded-md shadow-sm mt-1 block w-full">{{ $report['final']['comments'] }}</textarea>
                            <label>Mark:</label>
                            <input type="number" name="final[mark]" class="form-input rounded-md shadow-sm mt-1 block w-full" value={{ $report['final']['mark'] }}>
                        </div>
                        
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Submit Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


