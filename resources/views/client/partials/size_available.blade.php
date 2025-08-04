@php
    $firstAvailableSize = null;
@endphp
@foreach ($sizes as $key => $size)
    @php
        if (in_array(strtoupper($size->alias), array_map('strtoupper', $availableSizes)) && !$firstAvailableSize) {
            $firstAvailableSize = strtoupper($size->alias);
        }
    @endphp

    <span
        @unless (in_array(strtoupper(@$size->alias), $availableSizes))
        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="@translate('Hết hàng')"
    @endunless
        data-size-name="{{ @$size->name }}" data-size-fname="{{ @$size->alias }}"
        class="size-item {{ strtoupper($size->alias) == $firstAvailableSize ? 'active' : null }} {{ !in_array(strtoupper($size->alias), $availableSizes) ? 'item-disabled' : '' }}">
        {{ @$size->name }}
    </span>
@endforeach
