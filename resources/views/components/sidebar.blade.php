<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">PMR Wira</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">PMR</a>
        </div>
        <ul class="sidebar-menu">
            @if (auth()->user()->role == 'anggota')
                <li class="menu-header">Dashboard</li>
                <li class="nav-item {{ $type_menu === 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('anggota.dashboard') }}" class="nav-link"><i
                            class="far fa-fire"></i><span>Dashboard</span></a>
                </li>
                <li class="nav-item {{ $type_menu === 'uang-kas' ? 'active' : '' }}">
                    <a href="{{ route('anggota.uang-kas') }}" class="nav-link"><i
                            class="far fa-money-bill"></i><span>Uang Kas</span></a>
                </li>
                <li class="nav-item {{ $type_menu === 'proker' ? 'active' : '' }}">
                    <a href="{{ route('anggota.proker') }}" class="nav-link"><i class="far fa-hammer"></i><span>Program
                            Kerja</span></a>
                </li>
                <li class="nav-item {{ $type_menu === 'dokumentasi' ? 'active' : '' }}">
                    <a href="{{ route('anggota.dokumentasi') }}" class="nav-link"><i
                            class="far fa-camera"></i><span>Dokumentasi</span></a>
                </li>
                @if (!$berkas)
                    <li class="nav-item {{ $type_menu === 'pemberkasan' ? 'active' : '' }}">
                        <a href="{{ route('anggota.pemberkasan') }}" class="nav-link"><i
                                class="far fa-archive"></i><span>Berkas Diklat</span></a>
                    </li>
                @endif
                @if ($pemilu)
                    <li class="nav-item {{ $type_menu === 'pemilihan' ? 'active' : '' }}">
                        <a href="{{ route('anggota.dashboard.pemilu', ['type' => 'pemilihan']) }}" class="nav-link"><i
                                class="far fa-check-to-slot"></i></i><span>Pemilu</span></a>
                    </li>
                @endif
            @endif

            @auth
                @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus')
                    <li class="menu-header">Dashboard</li>
                    <li class="nav-item {{ $type_menu === 'dashboard' ? 'active' : '' }}">
                        @if (auth()->user()->role == 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i
                                    class="far fa-fire"></i><span>Dashboard</span></a>
                        @elseif (auth()->user()->role == 'pengurus')
                            <a href="{{ route('pengurus.dashboard') }}" class="nav-link"><i
                                    class="far fa-fire"></i><span>Dashboard</span></a>
                        @endif
                    </li>
                    <li class="nav-item dropdown {{ $type_menu === 'kepengurusan' ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                class="far fa-list-check"></i>
                            <span>Kepengurusan</span></a>
                        <ul class="dropdown-menu">
                            @if (auth()->user()->role == 'admin')
                                <li>
                                    <a class="nav-link"
                                        href="{{ route('admin.kepengurusan', ['type' => 'pengurus']) }}">Kelola
                                        Pengurus</a>
                                </li>
                            @endif
                            @if (auth()->user()->role == 'admin')
                                <li>
                                    <a class="nav-link"
                                        href="{{ route('admin.kepengurusan', ['type' => 'program-kerja']) }}">Program
                                        Kerja</a>
                                </li>
                            @elseif (auth()->user()->role == 'pengurus')
                                <li>
                                    <a class="nav-link"
                                        href="{{ route('pengurus.kepengurusan', ['type' => 'program-kerja']) }}">Program
                                        Kerja</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus')
                        <li class="nav-item dropdown {{ $type_menu === 'pengelolaan' ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="far fa-list-check"></i>
                                <span>Pengelolaan</span></a>
                            <ul class="dropdown-menu">
                                @if (auth()->user()->role == 'admin')
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('admin.pengelolaan', ['pengelolaan' => 'kelas']) }}">Kelas</a>
                                    </li>
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('admin.pengelolaan', ['pengelolaan' => 'unit']) }}">Unit</a>
                                    </li>
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('admin.pengelolaan', ['pengelolaan' => 'bidang']) }}">Bidang</a>
                                    </li>
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('admin.pengelolaan', ['pengelolaan' => 'uang-kas']) }}">Uang
                                            Kas</a>
                                    </li>
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('admin.pengelolaan', ['pengelolaan' => 'dokumentasi']) }}">Dokumentasi</a>
                                    </li>
                                @endif
                                @if (auth()->user()->role == 'pengurus')
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('pengurus.pengelolaan', ['pengelolaan' => 'uang-kas']) }}">Uang
                                            Kas</a>
                                    </li>
                                    <li>
                                        <a class="nav-link"
                                            href="{{ route('pengurus.pengelolaan', ['pengelolaan' => 'dokumentasi']) }}">Dokumentasi</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus')
                        <li class="menu-header">Keanggotaan</li>
                        <li class="nav-item {{ $type_menu === 'kelola_anggota' ? 'active' : '' }}">
                            <a href="{{ auth()->user()->role == 'admin' ? route('admin.kelola.anggota') : route('pengurus.kelola.anggota') }}"
                                class="nav-link"><i class="far fa-users"></i><span>Kelola Anggota</span></a>
                        </li>
                        <li class="nav-item dropdown {{ $type_menu === 'kelas' ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="far fa-school"></i>
                                <span>Kelas</span></a>
                            <ul class="dropdown-menu">
                                @foreach ($kelas as $item)
                                    <li>
                                        <a class="nav-link"
                                            href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard.daftar.anggota', ['kelas' => $item->slug]) : route('pengurus.dashboard.daftar.anggota', ['kelas' => $item->slug]) }}">{{ $item->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item dropdown {{ $type_menu === 'unit' ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fab fa-unity"></i>
                                <span>Unit</span></a>
                            <ul class="dropdown-menu">
                                @foreach ($unit as $item)
                                    <li>
                                        <a class="nav-link"
                                            href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard.daftar.anggota', ['unit' => $item->slug]) : route('pengurus.dashboard.daftar.anggota', ['unit' => $item->slug]) }}">{{ $item->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item dropdown {{ $type_menu === 'bidang' ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="far fa-bolt"></i>
                                <span>Bidang</span></a>
                            <ul class="dropdown-menu">
                                @foreach ($bidang as $item)
                                    <li>
                                        <a class="nav-link"
                                            href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard.daftar.anggota', ['bidang' => $item->slug]) : route('pengurus.dashboard.daftar.anggota', ['bidang' => $item->slug]) }}">{{ $item->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="menu-header">Diklat</li>
                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus')
                            @if (auth()->user()->role == 'admin')
                                <li class="nav-item {{ $type_menu === 'pemberkasan' ? 'active' : '' }}">
                                    <a href="{{ route('admin.pemberkasan') }}" class="nav-link"><i
                                            class="far fa-box-archive"></i></i><span>Pemberkasan</span></a>
                                </li>
                            @elseif (auth()->user()->role == 'pengurus')
                                <li class="nav-item {{ $type_menu === 'pemberkasan' ? 'active' : '' }}">
                                    <a href="{{ route('pengurus.pemberkasan') }}" class="nav-link"><i
                                            class="far fa-box-archive"></i></i><span>Pemberkasan</span></a>
                                </li>
                            @endif
                        @endif
                        @if (!$berkas->isEmpty())
                            <li class="nav-item dropdown {{ $type_menu === 'diklat-berkas' ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                        class="far fa-list-check"></i>
                                    <span>Berkas</span></a>
                                <ul class="dropdown-menu">
                                    @foreach ($berkas as $item)
                                        @if (auth()->user()->role == 'admin')
                                            <li>
                                                <a class="nav-link"
                                                    href="{{ route('admin.berkas.detail', $item->slug) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="nav-link"
                                                    href="{{ route('pengurus.berkas.detail', $item->slug) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        <li class="menu-header">E-Vote</li>
                        @if (auth()->user()->role == 'admin')
                            <li class="nav-item {{ $type_menu === 'pemilu-dashboard' ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard.pemilu', ['type' => 'dashboard']) }}" class="nav-link"><i
                                        class="far fa-tachometer-alt"></i></i><span>Dashboard Pemilu</span></a>
                            </li>
                            <li class="nav-item {{ $type_menu === 'pemilu-event' ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard.pemilu', ['type' => 'event']) }}" class="nav-link"><i
                                        class="far fa-check-to-slot"></i></i><span>Event</span></a>
                            </li>
                        @elseif (auth()->user()->role == 'pengurus')
                            <li class="nav-item {{ $type_menu === 'pemilu-dashboard' ? 'active' : '' }}">
                                <a href="{{ route('pengurus.dashboard.pemilu', ['type' => 'dashboard']) }}" class="nav-link"><i
                                        class="far fa-tachometer-alt"></i></i><span>Dashboard Pemilu</span></a>
                            </li>
                            <li class="nav-item {{ $type_menu === 'pemilu-event' ? 'active' : '' }}">
                                <a href="{{ route('pengurus.dashboard.pemilu', ['type' => 'event']) }}" class="nav-link"><i
                                        class="far fa-check-to-slot"></i></i><span>Event</span></a>
                            </li>
                        @endif
                        @if ($pemilu)
                            <li class="nav-item {{ $type_menu === 'pemilihan' ? 'active' : '' }}">
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard.pemilu', ['type' => 'pemilihan']) }}"
                                        class="nav-link"><i class="far fa-check-to-slot"></i></i><span>Pemilu</span></a>
                                @elseif (auth()->user()->role == 'pengurus')
                                    <a href="{{ route('pengurus.dashboard.pemilu', ['type' => 'pemilihan']) }}"
                                        class="nav-link"><i class="far fa-check-to-slot"></i></i><span>Pemilu</span></a>
                                @endif
                            </li>
                        @endif
                    @endif
                @endif
            @endauth
        </ul>

        {{-- <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div> --}}
    </aside>
</div>
