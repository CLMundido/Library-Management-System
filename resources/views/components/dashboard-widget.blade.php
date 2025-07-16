@props(['title', 'icon', 'color' => 'blue', 'value' => 0, 'prefix' => ''])

<div class="bg-white rounded-xl shadow border border-gray-100 p-6 hover:shadow-md transition">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-{{ $color }}-600 mt-1">
                {{ $prefix }}{{ $value }}
            </p>
        </div>
        <div class="w-10 h-10 bg-{{ $color }}-100 rounded-full flex items-center justify-center">
            <i class="{{ $icon }} text-{{ $color }}-600"></i>
        </div>
    </div>
</div>
