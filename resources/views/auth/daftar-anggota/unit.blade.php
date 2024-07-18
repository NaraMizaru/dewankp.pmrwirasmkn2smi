@extends('layouts.app')

@section('title', 'Daftar Anggoat ' . $thisUnit->name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Anggota Unit</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Daftar Anggota {{ $thisUnit->name }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $anggotaUnitCount }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Anggota By Unit</h4>
                        </div>
                        <div class="card-body">
                            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus' && auth()->user()->pengurus->type == 'inti 6')
                            <a href="{{ route('download.daftar-anggota-by.pdf', ['unit' => $thisUnit->slug]) }}"
                                class="float-right btn btn-danger mb-2"><i class="fa-regular fa-file-pdf"></i></a>
                            @endif
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                No
                                            </th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Unit</th>
                                            <th>Status</th>
                                            @if (auth()->user()->role == 'admin')
                                            <th>Action</th>
                                            @endif>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($anggota as $item)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>{{ $item->user->fullname }}</td>
                                                <td>
                                                    {{ $item->kelas->name }}
                                                </td>
                                                <td>{{ $item->unit->name }}</td>
                                                <td>
                                                    <div
                                                        class="badge {{ $item->status == 'pengkacuan' ? 'badge-success' : 'badge-danger' }} text-capitalize">
                                                        {{ $item->status }}</div>
                                                </td>
                                                @if (auth()->user()->role == 'admin')
                                                    <td>
                                                        <form
                                                        action="{{ $item->status == 'tidak pengkacuan' ? route('admin.anggota.upStatus', $item->user->username) : route('admin.anggota.upStatusBalok1', $item->user->username) }}"
                                                        method="POST">
                                                            @csrf
                                                            <button class="btn btn-dark"
                                                                {{ $item->status == 'pengkacuan' ? 'disabled' : '' }}
                                                                title="Naikan Anggota"><i
                                                                    class="fa-regular fa-arrow-up"></i></button>
                                                            <a href="{{ route('admin.daftar-anggota.detail', $item->user->username) }}"
                                                                class="btn btn-success" title="Lihat Detail Anggota"><i
                                                                    class="fa-regular fa-eye"></i></a>
                                                            <a href="{{ route('admin.daftar-anggota.edit', $item->user->username) }}"
                                                                class="btn btn-warning" title="Edit Detail Anggota"><i
                                                                    class="fa-regular fa-pen-to-square"></i></a>
                                                            <a href="{{ route('admin.delete.anggota', $item->user->username) }}"
                                                                id="btn-delete" class="btn btn-danger"
                                                                title="Hapus Anggota"><i
                                                                    class="fa-regular fa-trash"></i></a>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = $('#table-1').DataTable();
            const handleDeleteUangKas = (e) => {
                e.preventDefault();
                const target = e.currentTarget.getAttribute("href");
                swal({
                    title: "Apakah Kamu Yakin?",
                    text: "Sekali menghapus, maka kamu tidak dapat mengembalikannya lagi",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location.href = target;
                        swal("Success, anggota berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan anggota dibatalkan!");
                    }
                });
            }

            table.on('draw', () => {
                const deleteButtons = document.querySelectorAll('a[id="btn-delete"]');
                deleteButtons.forEach((button) => {
                    button.removeEventListener('click', handleDeleteUangKas);
                    button.addEventListener('click', handleDeleteUangKas);
                });
            });

            table.draw();
        });
    </script>
@endpush
