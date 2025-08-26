@props(['status'])

@if ($status == 'pending')
    <span class="px-2 py-1 rounded-md text-xs bg-yellow-500 text-white">
        {{ ucfirst($status) }}
    </span>
@endif
@if ($status == 'accepted')
    <span class="px-2 py-1 rounded-md text-xs bg-green-500 text-white">
        {{ ucfirst($status) }}
    </span>
@endif
@if ($status == 'rejected')
    <span class="px-2 py-1 rounded-md text-xs bg-red-500 text-white">
        {{ ucfirst($status) }}
    </span>
@endif
@if ($status == 'shortlisted')
    <span class="px-2 py-1 rounded-md text-xs bg-blue-500 text-white">
        {{ ucfirst($status) }}
    </span>
@endif
