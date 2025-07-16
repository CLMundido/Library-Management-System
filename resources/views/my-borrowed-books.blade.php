<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('My Borrowed Books') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-xl rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Borrowed Books List</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 text-left">Book Title</th>
                                <th class="px-4 py-2 text-left">Author</th>
                                <th class="px-4 py-2 text-left">Date Borrowed</th>
                                <th class="px-4 py-2 text-left">Due Date</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($borrowedBooks as $record)
                                <tr>
                                    <td class="px-4 py-2">{{ $record->book->title }}</td>
                                    <td class="px-4 py-2">{{ $record->book->author }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y') }}</td>
                                    <td class="px-4 py-2">{{ $record->due_date ? \Carbon\Carbon::parse($record->due_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                            @if ($record->status === 'returned')
                                                bg-green-100 text-green-800
                                            @elseif ($record->status === 'approved')
                                                bg-blue-100 text-blue-800
                                            @elseif ($record->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No borrowed books found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
