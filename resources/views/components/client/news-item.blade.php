<div class="news-item-layout {{ @$className }}">
    <div>
        <a class="news-img" href="{{ $item->href }}" title="{{ $item->name }}">
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="img-fluid" onerror="this.src='{{ asset('img/no-image.png') }}'">
        </a>
    </div>
    <div class="news-content">
        <a href="{{ $item->href }}" class="news-name" title="{{ $item->name }}">
            {{ $item->name }}
        </a>
        <div class="news-summary">
            {{ $item->summary }}
        </div>
        <div class="news-time-category">
            <span class="news-time">
                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.474 10.62L8.40732 9.38671C8.04732 9.17337 7.75398 8.66004 7.75398 8.24004V5.50671M14.6673 8.50004C14.6673 12.18 11.6807 15.1667 8.00065 15.1667C4.32065 15.1667 1.33398 12.18 1.33398 8.50004C1.33398 4.82004 4.32065 1.83337 8.00065 1.83337C11.6807 1.83337 14.6673 4.82004 14.6673 8.50004Z" stroke="#6B7280" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ $item->created_at->format('d-m-Y') }}
            </span>
            <svg width="5" height="5" viewBox="0 0 5 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="2.5" cy="2.5" r="2.5" fill="#ACB0B9"/>
            </svg>                                            
            <a class="news-category" href="{{ $item->category->href }}" title="{{ $item->category->name }}">
                {{ $item->category->name }}
            </a>
        </div>
    </div>
</div>