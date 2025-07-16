@props(['title', 'route', 'icon', 'color'])

<a href="{{ route($route) }}"
    class="flex items-center p-4 bg-{{ $color }}-50 rounded-lg hover:bg-{{ $color }}-100 transition group">
    <div class="w-10 h-10 bg-{{ $color }}-600 text-white rounded-lg flex items-center justify-center mr-4">
        <i class="{{ $icon }}"></i>
    </div>
    <div>
        <p class="font-semibold text-gray-900">{{ $title }}</p>
        <p class="text-sm text-gray-600">Go to {{ $title }}</p>
    </div>
</a>