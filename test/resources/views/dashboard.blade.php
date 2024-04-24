<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight pl-10">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                <div class="p-6 text-gray-900">
                    <button type="button" onclick="window.location='{{ url("exams") }}'">Exams</button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                <div class="p-6 text-gray-900">
                    <button type="button" onclick="window.location='{{ url("reports") }}'">Reports</button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg m-8">
                <div class="p-6 text-gray-900">
                    <button type="button" onclick="window.location='{{ url("grading") }}'">Grading</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

