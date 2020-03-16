@can("viewAny", \App\Meta::class)
    <li class="nav-item">
        <a href="{{ route('admin.meta.index') }}"
           class="nav-link{{ strstr($currentRoute, 'admin.meta.') !== FALSE ? ' active' : '' }}">
            @isset($ico)
                <i class="{{ $ico }}"></i>
            @endisset
            Мета
        </a>
    </li>
@endcan