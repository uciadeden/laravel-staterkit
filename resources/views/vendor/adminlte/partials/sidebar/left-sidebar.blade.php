<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
    @include('adminlte::partials.common.brand-logo-xl')
    @else
    @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
            data-widget="treeview" role="menu"
            @if(config('adminlte.sidebar_nav_animation_speed') != 300)
            data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
            @endif
            @if(!config('adminlte.sidebar_nav_accordion'))
            data-accordion="false"
            @endif>
            
            {{-- Sidebar menu items --}}
            @php
            // Ambil user yang sedang login
            $user = auth()->user();

            // Ambil menus berdasarkan role yang dimiliki oleh user
            $menus = $user->roles->flatMap(function ($role) {
            return $role->menus; // Ambil menu dari setiap role
        });
        
        $menus = $menus->unique('id'); // Menggunakan ID menu untuk memastikan keunikan
        $categories = $menus->groupBy('category'); // Kelompokkan menu berdasarkan kategori
        @endphp

        {{-- Menampilkan menu dengan kategori --}}
        @foreach($categories as $category => $menusInCategory)
        {{-- Cek jika kategori null, tampilkan menu langsung --}}
        @if($category === null || $category == "")
        @foreach($menusInCategory as $menu)
        <li class="nav-item">
            <a href="{{ url($menu->url) }}" class="nav-link {{ request()->is(ltrim($menu->url, '/').'*') ? 'active' : '' }}">
                <i class="nav-icon {{ $menu->icon }}"></i>
                <p>{{ $menu->name }}</p>
            </a>
        </li>
        @endforeach
        @else
        {{-- Tampilkan kategori dan menu jika kategori tidak null --}}
        @php
        // Cek apakah ada menu yang aktif dalam kategori ini
        $isCategoryActive = $menusInCategory->contains(function($menu) {
        return request()->is(ltrim($menu->url, '/').'*');
    });
    @endphp

    <li class="nav-item has-treeview {{ $isCategoryActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i> <!-- Icon kategori -->
            <p>
                {{ $category }}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @foreach($menusInCategory as $menu)
            <li class="nav-item">
                <a href="{{ url($menu->url) }}" class="nav-link {{ request()->is(ltrim($menu->url, '/').'*') ? 'active' : '' }}">
                    <i class="nav-icon {{ $menu->icon }}"></i>
                    <p>{{ $menu->name }}</p>
                </a>
            </li>
            @endforeach
        </ul>
    </li>
    @endif
    @endforeach

</ul>
</nav>
</div>
</aside>
