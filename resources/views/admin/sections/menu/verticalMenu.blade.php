<aside id="layout-menu" class="layout-menu menu-vertical menu" style="background-color: white">
    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1 mt-2">
                @include('_partials.macros', ['height' => 100])
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
        </a>
    </div>
    @php
        $user = Auth::user();
        $permissions = $user->rolePermission->toArray();
        // dd($permissions);  

    @endphp
    <div class="menu-inner-shadow"></div>

    @if (count($permissions) > 0)
        <ul class="menu-inner py-1">
            @foreach ($menuData[0]->menu as $menu)
                {{-- adding active and open class if child is active --}}

                {{-- menu headers --}}
                @if (isset($menu->menuHeader))
                    <li class="menu-header fw-medium mt-4">
                        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                    </li>
                @else
                    {{-- active menu method --}}
                    @php
                        $activeClass = null;
                        $currentRouteName = Route::currentRouteName();
                        if ($currentRouteName === $menu->slug) {
                            $activeClass = 'active open';
                        } elseif (isset($menu->submenu)) {
                            if (gettype($menu->slug) === 'array') {
                                $show = false; //muốn hiển thị menu thì sửa ở đây

                                foreach ($menu->slug as $slug) {
                                    foreach ($permissions as $item) {
                                        if (strpos($item['route'], $slug) !== false && $item['permission'] == 1) {
                                            $show = true;
                                        }
                                    }
                                    if (
                                        str_contains($currentRouteName, $slug) and
                                        (strpos($currentRouteName, $slug) === 0 or
                                            strpos(url()->current(), $slug) === 0)
                                    ) {
                                        $activeClass = 'active open';
                                    }
                                }
                            } else {
                                if (
                                    str_contains($currentRouteName, $menu->slug) and
                                    strpos($currentRouteName, $menu->slug) === 0
                                ) {
                                    $activeClass = 'active open';
                                }
                            }
                        }
                    @endphp
                    @if ($show)
                        <li class="menu-item {{ $activeClass }}">
                            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                                class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                                @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                                @isset($menu->icon)
                                    <i class="{{ $menu->icon }}"></i>
                                @endisset
                                <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                                @isset($menu->badge)
                                    <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}
                                    </div>
                                @endisset
                            </a>

                            {{-- submenu --}}
                            @isset($menu->submenu)
                                @include('admin.sections.menu.submenu', ['menu' => $menu->submenu])
                            @endisset
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    @endif
</aside>
