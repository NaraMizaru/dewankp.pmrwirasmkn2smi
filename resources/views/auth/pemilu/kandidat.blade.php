@extends('layouts.app')

@section('title', 'Kandidat')

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
                <h1 class="text-primary">Event Pemilu | {{ $pemilu->name }}</h1>
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
                                    <h4>Daftar Kandidat | {{ $pemilu->name }}</h4>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <button class="btn btn-primary" data-slug="{{ $pemilu->slug }}"
                                    data-role={{ auth()->user()->role }} data-toggle="modal"
                                    data-target="#addKandidatModal"><i class="far fa-plus mr-2"></i>Tambah Kandidat</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama</th>
                                            <th class="text-center">Gambar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kandidat as $item)
                                            <tr>
                                                <td class="text-center text-capitalize">{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-capitalize">
                                                    <a href="{{ asset('/storage/' . $item->gambar) }}" target="_blank"
                                                        class="btn btn-success d-block"><i
                                                            class="fa-regular fa-eye"></i></a>
                                                </td>
                                                <td>
                                                    <button data-target='#editEventModal' data-toggle="modal"
                                                        class="btn btn-warning" data-slug="{{ $pemilu->slug }}"
                                                        data-name='{{ $item->name }}'
                                                        data-description='{{ $item->description }}'
                                                        data-id='{{ $item->id }}'
                                                        data-role="{{ auth()->user()->role }}"
                                                        data-visi-misi="{{ $item->visi_misi }}"><i
                                                            class="far fa-pen-to-square mr-2"></i>Edit</button>
                                                    @if (auth()->user()->role == 'admin')
                                                        <a href="{{ route('admin.dashboard.pemilu.kandidat.delete', [$pemilu->slug, 'kandidat_id' => $item->id]) }}"
                                                            class="btn btn-danger" id="btn-delete"><i
                                                                class="far fa-trash mr-2"></i>Delete</a>
                                                    @elseif (auth()->user()->role == 'pengurus')
                                                        <a href="{{ route('pengurus.dashboard.pemilu.kandidat.delete', [$pemilu->slug, 'kandidat_id' => $item->id]) }}"
                                                            class="btn btn-danger" id="btn-delete"><i
                                                                class="far fa-trash mr-2"></i>Delete</a>
                                                    @endif
                                                </td>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="addKandidatModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="needs-validation" novalidate='' method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="name">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control"
                                autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Gambar</label>
                            <input type="file" name="gambar" id="gambar" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="addVisiMisi">Visi Misi</label>
                            <textarea class="form-control" id="addVisiMisi" name="visi-misi"></textarea>
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
                <form action class="needs-validation" novalidate='' method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Event</label>
                            <input type="text" name="name" id="name" class="form-control"
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="name">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control"
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="name">Gambar</label>
                            <input type="file" name="gambar" id="gambar" class="form-control"
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="editVisiMisi">Visi Misi</label>
                            <textarea class="form-control" id="editVisiMisi" name="visi-misi"></textarea>
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

            const handleAddKandidat = (e) => {
                const button = e.currentTarget
                const slug = button.getAttribute('data-slug')
                const role = button.getAttribute('data-role');

                const modal = document.getElementById('addKandidatModal');
                const formAdd = modal.querySelector('form');

                let formAction;
                if (role == 'admin') {
                    formAction = '{{ route('admin.dashboard.pemilu.kandidat.add', ':slug') }}'
                } else if (role == 'pengurus') {
                    formAction = '{{ route('pengurus.dashboard.pemilu.kandidat.add', ':slug') }}'
                }
                formAdd.setAttribute('action', formAction.replace(':slug', slug))
            }

            const handleEditEvent = (e) => {
                const button = e.currentTarget
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const description = button.getAttribute('data-description');
                const slug = button.getAttribute('data-slug');
                const role = button.getAttribute('data-role');
                const visiMisi = button.getAttribute('data-visi-misi');

                const modal = document.getElementById('editEventModal');
                const nameInput = modal.querySelector('#name');
                const descriptionInput = modal.querySelector('#description');
                const formEdit = modal.querySelector('form');
                const textarea = modal.querySelector('textarea');

                nameInput.value = name;
                descriptionInput.value = description;
                textarea.value = visiMisi;

                let formAction;
                if (role == 'admin') {
                    formAction = "{{ route('admin.dashboard.pemilu.kandidat.edit', ':slug') }}?kandidat_id=" +
                        id
                } else if (role == 'pengurus') {
                    formAction = "{{ route('admin.dashboard.pemilu.kandidat.edit', ':slug') }}?kandidat_id=" +
                        id
                }
                formEdit.setAttribute('action', formAction.replace(':slug', slug));
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
                        swal("Success, anggota berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan anggota dibatalkan!");
                    }
                });
            }

            table.on('draw', () => {
                const addButton = document.querySelectorAll('button[data-target="#addKandidatModal"]');
                addButton.forEach((button) => {
                    button.removeEventListener('click', handleAddKandidat);
                    button.addEventListener('click', handleAddKandidat);
                });

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
