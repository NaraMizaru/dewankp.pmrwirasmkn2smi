@extends('layouts.app')

@section('title', 'Event')

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
                <h1 class="text-primary">Event Pemilu</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h4>Daftar Event</h4>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addEventModal"><i
                                        class="far fa-plus mr-2"></i>Tambah Event</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th class="text-center">Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pemilu as $item)
                                        <tr>
                                            <td class="text-center text-capitalize">{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-capitalize">
                                                <div class="badge {{ $item->status == 'aktif' ? 'badge-success' : 'badge-danger' }} w-100">{{ $item->status }}</div>
                                            </td>
                                            <td>
                                                <div class="d-block">
                                                    @if (auth()->user()->role == 'admin')
                                                        <a href="{{ route('admin.dashboard.pemilu.slug', [$item->slug, 'type' => 'kandidat']) }}" class="btn btn-success d-block"><i class="far fa-ranking-star mr-2"></i>Kandidat</a>
                                                        <a href="{{ route('admin.dashboard.pemilu.slug', [$item->slug, 'type' => 'result']) }}" class="btn btn-secondary d-block mt-2"><i class="far fa-chart-area mr-2"></i>Result</a>
                                                        <a href="{{ route('admin.dashboard.pemilu.slug', [$item->slug, 'type' => 'vote-logs']) }}" class="btn btn-info d-block mt-2"><i class="fa-regular fa-memo-pad mr-2"></i>Vote Logs</a>
                                                        <button data-target='#editEventModal' data-toggle="modal" class="btn btn-warning w-100 mt-2" data-name='{{ $item->name }}' data-description='{{ $item->description }}' data-slug='{{ $item->slug }}' data-role='{{ auth()->user()->role }}'><i class="far fa-pen-to-square mr-2"></i>Edit</button>
                                                        <a href="{{ route('admin.dashboard.pemilu.delete', $item->slug) }}" class="btn btn-danger d-block mt-2" id="btn-delete"><i class="far fa-trash mr-2"></i>Delete</a>
                                                    @elseif (auth()->user()->role == 'pengurus')
                                                        <a href="{{ route('pengurus.dashboard.pemilu.slug', [$item->slug, 'type' => 'kandidat']) }}" class="btn btn-success d-block"><i class="far fa-ranking-star mr-2"></i>Kandidat</a>
                                                        <a href="{{ route('pengurus.dashboard.pemilu.slug', [$item->slug, 'type' => 'result']) }}" class="btn btn-secondary d-block mt-2"><i class="far fa-chart-area mr-2"></i>Result</a>
                                                        <a href="{{ route('pengurus.dashboard.pemilu.slug', [$item->slug, 'type' => 'vote-logs']) }}" class="btn btn-info d-block mt-2"><i class="fa-regular fa-memo-pad mr-2"></i>Vote Logs</a>
                                                        <button data-target='#editEventModal' data-toggle="modal" class="btn btn-warning w-100 mt-2" data-name='{{ $item->name }}' data-description='{{ $item->description }}' data-slug='{{ $item->slug }}' data-role='{{ auth()->user()->role }}'><i class="far fa-pen-to-square mr-2"></i>Edit</button>
                                                        <a href="{{ route('pengurus.dashboard.pemilu.delete', $item->slug) }}" class="btn btn-danger d-block mt-2" id="btn-delete"><i class="far fa-trash mr-2"></i>Delete</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addEventModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if (auth()->user()->role == 'admin')
                <form action='{{ route('admin.dashboard.pemilu.add') }}' class="needs-validation" novalidate=''
                    method="POST">
                @elseif (auth()->user()->role == 'pengurus')
                <form action='{{ route('pengurus.dashboard.pemilu.add') }}' class="needs-validation" novalidate=''
                    method="POST">
                @endif
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Event</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="name">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="tidak aktif">Tidak Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <small class="text-danger">*Aktif terlihat oleh anggota, tidak hanya terlihat oleh
                                pengurus</small>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="editEventModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action class="needs-validation" novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Event</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="name">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="tidak aktif">Tidak Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <small class="text-danger">*Aktif terlihat oleh anggota, tidak aktif hanya terlihat oleh
                                pengurus</small>
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

            const handleEditEvent = (e) => {
                const button = e.currentTarget
                const name = button.getAttribute('data-name');
                const description = button.getAttribute('data-description');
                const slug = button.getAttribute('data-slug');
                const role = button.getAttribute('data-role');

                const modal = document.getElementById('editEventModal');
                const nameInput = modal.querySelector('#name');
                const descriptionInput = modal.querySelector('#description');
                const formEdit = modal.querySelector('form');

                nameInput.value = name;
                descriptionInput.value = description;
                
                let formAction;
                if (role == 'admin') {
                    formAction = '{{ route("admin.dashboard.pemilu.edit", ":slug") }}'
                } else if (role == 'pengurus') {
                    formAction = '{{ route("pengurus.dashboard.pemilu.edit", ":slug") }}'
                }
                formEdit.setAttribute('action', formAction.replace(':slug', slug))
            }

            const handleDeleteButton = (e) => {
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
                        swal("Success, pemilu berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan pemilu dibatalkan!");
                    }
                });
            }

            table.on('draw', () => {
                const editButtons = document.querySelectorAll('button[data-target="#editEventModal"]');
                editButtons.forEach((button) => {
                    button.removeEventListener('click', handleEditEvent);
                    button.addEventListener('click', handleEditEvent);
                });

                const deleteButtons = document.querySelectorAll('a[id="btn-delete"]');
                deleteButtons.forEach((button) => {
                    button.removeEventListener('click', handleDeleteButton);
                    button.addEventListener('click', handleDeleteButton);
                });
            });

            table.draw();
        });
    </script>
@endpush
