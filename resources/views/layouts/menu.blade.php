<li class="side-menus {{ Request::is('*') ? 'active' : '' }}">    
    @role('Sysadmin')
    <a class="nav-link" href="/home">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
    <a class="nav-link" href="/usuarios">
        <i class=" fas fa-users"></i><span>Usuarios</span>
    </a>
    <a class="nav-link" href="/roles">
        <i class=" fas fa-user-lock"></i><span>Roles</span>
    </a>
    @endrole

    <a class="nav-link" href="/creditos">
        <i class=" fas fa-blog"></i><span>Créditos</span>
    </a>

    @hasanyrole('Sysadmin|Manager|Administrador')
    <a class="nav-link" href="/llaves">
        <i class=" fas fa-key"></i><span>Llaves de acceso</span>
    </a>
    @endhasanyrole
</li>
