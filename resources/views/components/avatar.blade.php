@props([
    'user',
    'size' => 'h-12 w-12',
    'textSize' => 'text-sm',
])

@php
    $imageUrl = $user->profileImageUrl();
@endphp

@if ($imageUrl)
    <img
        src="{{ $imageUrl }}"
        alt="{{ $user->name }} profile image"
        {{ $attributes->class([$size, 'rounded-2xl object-cover shadow-sm ring-1 ring-slate-900/10']) }}
    >
@else
    <span {{ $attributes->class([$size, $textSize, 'avatar-fallback']) }}>
        {{ $user->initials() }}
    </span>
@endif
