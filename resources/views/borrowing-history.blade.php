<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Borrowing History') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">All Borrow Records</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 text-left">Title</th>
                                <th class="px-4 py-2 text-left">Author</th>
                                <th class="px-4 py-2 text-left">Borrowed At</th>
                                <th class="px-4 py-2 text-left">Due Date</th>
                                <th class="px-4 py-2 text-left">Returned At</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($records as $record)
                                <tr>
                                    <td class="px-4 py-2">{{ $record->book->title ?? 'Unknown' }}</td>
                                    <td class="px-4 py-2">{{ $record->book->author ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $record->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-2">{{ $record->due_date ? \Carbon\Carbon::parse($record->due_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $record->return_date ? \Carbon\Carbon::parse($record->returned_at)->format('M d, Y') : '-' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs rounded-full font-medium
                                            @if ($record->status === 'returned')
                                                bg-green-100 text-green-800
                                            @elseif ($record->status === 'approved')
                                                bg-blue-100 text-blue-800
                                            @elseif ($record->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @elseif ($record->status === 'rejected')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-gray-100 text-gray-600
                                            @endif">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                        You have no borrowing history yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
