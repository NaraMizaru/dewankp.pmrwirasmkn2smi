<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar bg-primary">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                @if (auth()->user()->profile_image == null)
                    <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="mr-1">
                @else
                    <img alt="image" src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="mr-1">
                @endif
                <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->username }}</div>
            </a>
            @auth
                <div class="dropdown-menu dropdown-menu-right">
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'pengurus')
                        <div class="dropdown-title">Welcome,
                            {{ auth()->user()->fullname }}</div>
                        @if (auth()->user()->role == 'admin')
                            <a href="{{ route('admin.profile', auth()->user()->username) }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                        @else
                            <a href="{{ route('pengurus.profile', auth()->user()->username) }}"
                                class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                        @endif
                        <a href="{{ route('admin.dashboard.setting') }}" class="dropdown-item has-icon">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
                    <a role="button" data-target="#confirmLogout" data-toggle="modal"
                        class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            @endauth
        </li>
    </ul>
</nav>

<div class="modal fade" tabindex="-1" role="dialog" id="confirmLogout">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin ingin logout?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="{{ route('logout') }}" class="btn btn-primary">Yes</a>
            </div>
        </div>
    </div>
</div>
