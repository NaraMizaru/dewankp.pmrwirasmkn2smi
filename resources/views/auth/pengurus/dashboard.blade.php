@extends('layouts.app')

@section('title', 'Dashboard Pengurus')

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
                <h1 class="text-primary">Dashboard</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Anggota</h4>
                            </div>
                            <div class="card-body">
                                {{ $anggotaCount }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Anggota &mdash; {{ $pengurus->unit->name }}</h4>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('download.daftar-anggota-by.pdf', ['unit' => $pengurus->unit->slug]) }}"
                                class="float-right btn btn-danger mb-2"><i class="fa-regular fa-file-pdf"></i></a>
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
                                            <th>Action</th>
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
                                                <form
                                                    action="{{ $item->status == 'tidak pengkacuan' ? route('pengurus.anggota.upStatus', $item->user->username) : route('pengurus.anggota.upStatusBalok1', $item->user->username) }}"
                                                    method="POST">
                                                    @csrf
                                                    <td>
                                                        <button
                                                            class="btn {{ $item->status == 'tidak pengkacuan' ? 'btn-info' : 'btn-dark' }} "
                                                            {{ $pengurus->status == 'balok 2' ? '' : 'disabled' }}
                                                            style="cursor: {{ $pengurus->status == 'balok 2' ? '' : 'not-allowed' }}"><i
                                                                class="fa-regular fa-arrow-up"></i></button>
                                                        <a href="{{ route('pengurus.daftar-anggota.detail', $item->user->username) }}"
                                                            class="btn btn-success"><i class="fa-regular fa-eye"></i></a>
                                                        <a href="{{ route('pengurus.daftar-anggota.edit', $item->user->username) }}"
                                                            class="btn btn-warning"><i
                                                                class="fa-regular fa-pen-to-square"></i></a>
                                                        <a href="{{ route('pengurus.delete.anggota', $item->user->username) }}"
                                                            id="btn-delete" class="btn btn-danger"><i
                                                                class="fa-regular fa-trash"></i></a>
                                                    </td>
                                                </form>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Program Kerja &mdash; {{ $pengurus->unit->name }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <a href="{{ route('download.proker.pdf', ['type' => $pengurus->unit->slug]) }}"
                                    class="btn btn-danger float-right"><i class="fa-regular fa-file-pdf"></i></a>
                                <button data-target="#addProkerModal" data-toggle="modal"
                                    class="btn btn-success float-right mb-3 mr-2"><i
                                        class="fa-regular fa-plus"></i></button>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($proker->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center">Proker tidak tersedia</td>
                                            </tr>
                                        @else
                                            @foreach ($proker as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td class="text-center text-capitalize">
                                                        @if ($item->status == 'selesai')
                                                            <div class="badge badge-success">
                                                                {{ $item->status }}
                                                            </div>
                                                        @elseif ($item->status == 'ongoing')
                                                            <div class="badge badge-warning">
                                                                {{ $item->status }}
                                                            </div>
                                                        @elseif ($item->status == 'tidak selesai')
                                                            <div class="badge badge-danger">
                                                                {{ $item->status }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->status == 'tidak selesai')
                                                            <form action="{{ route('pengurus.startProker', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button
                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                            </form>
                                                        @elseif ($item->status == 'ongoing')
                                                            <button data-target="#finishProkerModal" data-toggle="modal"
                                                                data-name='{{ $item->name }}'
                                                                data-id='{{ $item->id }}'
                                                                data-unit-name='{{ $item->name }}'
                                                                data-user-role={{ auth()->user()->role }}
                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                        @elseif ($item->status == 'selesai')
                                                            <form action="">
                                                                <button class="btn mb-2 mt-2 btn-sm w-100 btn-dark"
                                                                    style="cursor: not-allowed" disabled>Finished</button>
                                                            </form>
                                                        @endif
                                                        <a href="{{ route('pengurus.deleteProker', $item->id) }}"
                                                            id="btn-delete" class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                            <i class="fa-regular fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addProkerModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Proker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pengurus.addProker') }}" class="needs-validation" novalidate=''
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="unit_id">Unit Proker</label>
                            <select name="unit_id" id="unit_id" class="form-control ">
                                <option value="{{ $pengurus->unit->id }}">{{ $pengurus->unit->name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Proker</label>
                            <input type="text" name="name" id="name" class="form-control"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="finishProkerModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Proker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action class="needs-validation" novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Proker</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Selesai Proker</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" autocomplete="off"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="dokumentasi_id">Dokumentasi Proker</label>
                            <select name="dokumentasi_id" id="dokumentasi_id" class="form-control">
                                <option value="NULL">Pilih Dokumentasi</option>
                                @foreach ($dokumentasi as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
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

            const handleFinishButton = (e) => {
                const button = e.currentTarget
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const unitName = button.getAttribute('data-unit-name');
                const userRole = button.getAttribute('data-user-role');
                console.log(userRole)

                const modal = document.getElementById('finishProkerModal')
                const modalTitle = modal.querySelector('.modal-title');
                const unitSelect = modal.querySelector('#unit_id')
                const nameInput = modal.querySelector('#name')
                const formFinish = modal.querySelector('form')

                modalTitle.textContent = 'Finish Proker ' + unitName;
                nameInput.value = name;

                let formAction;
                if (userRole == 'admin') {
                    formAction = `{{ route('admin.finishProker') }}?id=` + id
                } else if (userRole == 'pengurus') {
                    formAction = `{{ route('pengurus.finishProker') }}?id=` + id
                }
                formFinish.setAttribute('action', formAction);
            }

            const finishButtons = document.querySelectorAll('button[data-target="#finishProkerModal"]')
            finishButtons.forEach((button) => {
                button.removeEventListener('click', handleFinishButton);
                button.addEventListener('click', handleFinishButton);
            });


            const handleDelete = (e) => {
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
                        swal("Success, berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan dibatalkan!");
                    }
                });
            }

            table.on('draw', () => {
                const deleteButtons = document.querySelectorAll('a[id="btn-delete"]');
                deleteButtons.forEach((button) => {
                    button.removeEventListener('click', handleDelete);
                    button.addEventListener('click', handleDelete);
                });
            });

            table.draw();
        });
    </script>
@endpush
