<nav class="breadcrumb-layout" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg fill='none' xmlns='http://www.w3.org/2000/svg'  width='20' height='20'%3E%3Cpath d='M7.7583 15L11.8745 10.8838C12.3606 10.3977 12.3606 9.60227 11.8745 9.11616L7.7583 5' stroke='%23BFBFBF' stroke-width='1.5' stroke-miterlimit='10' stroke-linecap='round' stroke-linejoin='round' /%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('client.home') }}">Trang chủ</a>
        </li>
        
        @if (isset($breadcrumbs))
        
            @foreach ($breadcrumbs as $key => $breadcrumb) 
                @if ($key == count($breadcrumbs) - 1)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $breadcrumb->name }}
                    </li>
                @else 
                    <li class="breadcrumb-item" aria-current="page" >
                        <a href="{{ $breadcrumb->href }}">
                            {{ @$breadcrumb->name ?: @$breadcrumb->title }}
                        </a>
                    </li>
                @endif
            @endforeach
        @endif 
    </ol>
</nav>