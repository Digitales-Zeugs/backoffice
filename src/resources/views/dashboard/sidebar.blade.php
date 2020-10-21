<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link">
        <span class="brand-text font-weight-light">SADAIC</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/" class="nav-link @if(Request::path() == '/') active @endif">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/profiles" class="nav-link @if(Request::path() == 'profiles') active @endif">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Actualización de Datos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/works" class="nav-link @if(Request::path() == 'works') active @endif">
                        <i class="nav-icon fas fa-folder-plus"></i>
                        <p>Registros de Obras</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/members" class="nav-link @if(Request::path() == 'members') active @endif">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>Registros de Socios</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
