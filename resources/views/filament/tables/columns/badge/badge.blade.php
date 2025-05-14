@props(['color' => 'primary'])

<span @class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
    'bg-primary-100 text-primary-800' => $color === 'primary',
    'bg-gray-100 text-gray-800' => $color === 'gray',
    'bg-red-100 text-red-800' => $color === 'danger',
    'bg-yellow-100 text-yellow-800' => $color === 'warning',
    'bg-green-100 text-green-800' => $color === 'success',
    'bg-blue-100 text-blue-800' => $color === 'info',
])>
    {{ $slot }}
</span>
