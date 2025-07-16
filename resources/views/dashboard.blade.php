<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Borrowed Books Widget -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Books Borrowed</h3>
                    <div class="text-4xl font-bold text-indigo-600">{{ $borrowedBooks }}</div>
                </div>

                <!-- Overdue Books Widget -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Overdue Books</h3>
                    <div class="text-4xl font-bold text-red-600">{{ $overdueBooks }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>