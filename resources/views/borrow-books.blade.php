<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Borrow Books') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 text-green-600 font-medium">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-4 text-red-600 font-medium">{{ session('error') }}</div>
            @endif

            <!-- Search Bar -->
            <form method="GET" action="{{ route('borrow-books') }}" class="mb-6">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search books by title or author..." 
                    class="w-full sm:w-1/2 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-300"
                >
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($books as $book)
                    <div class="bg-white p-4 shadow rounded-lg">
                        <div class="h-48 bg-gray-100 rounded mb-2 flex items-center justify-center">
                            @if ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Book Cover" class="h-full object-contain rounded">
                            @else
                                <span class="text-gray-400">No Cover</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-700 mb-1">by {{ $book->author }}</p>
                        <p class="text-sm mb-2">Available Copies: {{ $book->copies }}</p>

                        @if ($book->copies > 0)
                            <form action="{{ route('borrow-books.store', $book->id) }}" method="POST">
                                @csrf

                                <label for="return_date_{{ $book->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    Select Return Date:
                                </label>
                                <input type="date" name="return_date" id="return_date_{{ $book->id }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm mb-3"
                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    max="{{ \Carbon\Carbon::now()->addDays(15)->format('Y-m-d') }}"
                                    required>

                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                    Borrow
                                </button>
                            </form>
                        @else
                            <span class="inline-block w-full text-center bg-red-100 text-red-600 py-2 px-4 rounded">
                                Out of Stock
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-600">No books found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
