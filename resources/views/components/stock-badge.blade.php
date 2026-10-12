@props(['product'])
@php
    $map = ['in' => ['success', 'IN STOCK'], 'low' => ['warning', 'LOW STOCK'], 'out' => ['danger', 'OUT OF STOCK']];
    [$class, $text] = $map[$product->status];
@endphp
<span class="badge {{ $class }}">{{ $text }}</span>
