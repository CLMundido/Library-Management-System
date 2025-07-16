<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Penalty Notice') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-4">Your Penalties</h3>

                @if ($penalties->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 text-left">Book Title</th>
                                    <th class="px-4 py-2 text-left">Days Late</th>
                                    <th class="px-4 py-2 text-left">Reason</th>
                                    <th class="px-4 py-2 text-left">Amount (₱)</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($penalties as $penalty)
                                    <tr>
                                        <td class="px-4 py-2">
                                            {{ $penalty->borrowRecord->book->title ?? 'Unknown' }}
                                        </td>
                                        <td class="px-4 py-2">{{ $penalty->days_late }}</td>
                                        <td class="px-4 py-2">{{ $penalty->reason ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-red-600 font-semibold">₱{{ number_format($penalty->amount, 2) }}</td>
                                        <td class="px-4 py-2">
                                            @if ($penalty->paid)
                                                <span class="text-green-600 font-medium">Paid</span>
                                            @else
                                                <span class="text-red-600 font-medium">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-semibold">Total Unpaid Penalty:</td>
                                    <td colspan="2" class="px-4 py-3 font-bold text-red-700 text-lg">
                                        ₱{{ number_format($total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">You have no penalties.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
