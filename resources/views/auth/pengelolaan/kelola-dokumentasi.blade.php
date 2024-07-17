@extends('layouts.app')

@section('title', 'Pengelolaan Dokumentasi')

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
                <h1 class="text-primary">Pengelolaan Dokumentasi</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card p-3">
                        <div class="card-header">
                            <h4>List Dokumentasi</h4>
                        </div>
                        <button data-target="#addDokumentasiModal" data-toggle="modal"
                            class="btn btn-success mb-3 float-right">
                            New Dokumentasi
                        </button>
                        @if ($dokumentasi->isEmpty()) 
                            <a type="button" class="card border mt-3 card-default mb-3" style="text-decoration: none">
                                <div class="card-body">
                                    <h5 class="mb-1 text-center">Dokumentasi Tidak Tersedia</h5>
                                </div>
                            </a>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <td>Dokumentasi</td>
                                            <td class="text-right">Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dokumentasi as $item)
                                            <tr>
                                                <td>
                                                    @if (auth()->user()->role == 'admin')
                                                        <a href="{{ route('admin.gallery.dokumentasi', $item->slug) }}" class="card border mt-3 card-default mb-3"
                                                        style="text-decoration: none">
                                                    @elseif (auth()->user()->role == 'pengurus')
                                                        <a href="{{ route('pengurus.gallery.dokumentasi', $item->slug) }}" class="card border mt-3 card-default mb-3"
                                                        style="text-decoration: none">
                                                    @endif
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-8">
                                                                    <h5 class="mb-1">{{ $item->name }}</h5>
                                                                    <p class="text-danger text-capitalize">Status :
                                                                        {{ $item->status }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-right">
                                                    <button data-target="#editDokumentasiModal"
                                                        data-role='{{ auth()->user()->role }}'
                                                        data-name='{{ $item->name }}' data-slug='{{ $item->slug }}'
                                                        data-toggle="modal" class="btn btn-warning"><i
                                                            class="fa-regular fa-pen-to-square"></i></button>
                                                    @if (auth()->user()->role == 'admin')
                                                        <a href="{{ route('admin.pengelolaan.dokumentasi.delete', $item->slug) }}"
                                                        id='btn-delete' class="btn btn-danger"><i class="fa-regular fa-trash"></i></a>
                                                    @elseif (auth()->user()->role == 'pengurus')
                                                        <a href="{{ route('pengurus.pengelolaan.dokumentasi.delete', $item->slug) }}"
                                                        id='btn-delete' class="btn btn-danger"><i class="fa-regular fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addDokumentasiModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokumentasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if (auth()->user()->role == 'admin')
                    <form action="{{ route('admin.pengelolaan.dokumentasi.add') }}" class="needs-validation" novalidate=''
                        method="POST">
                    @elseif (auth()->user()->role == 'pengurus')
                        <form action="{{ route('pengurus.pengelolaan.dokumentasi.add') }}" class="needs-validation"
                            novalidate='' method="POST">
                @endif
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Dokumentasi</label>
                        <input type="text" name="name" id="name" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                        <small class="text-danger">*Public terlihat oleh anggota, Private hanya terlihat oleh
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

    <div class="modal fade" tabindex="-1" role="dialog" id="editDokumentasiModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bidang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action class="needs-validation" novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Dokumentasi</label>
                            <input type="text" name="name" id="name" class="form-control"
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                            <small class="text-danger">*Public terlihat oleh anggota, Private hanya terlihat oleh
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
        document.addEventListener('DOMContentLoaded', () => {
            const table = $('#table-1').DataTable();

            const handleEditDokumentasi = (e) => {
                const button = e.currentTarget;
                const name = button.getAttribute('data-name');
                const slug = button.getAttribute('data-slug');
                const role = button.getAttribute('data-role');

                const modal = document.getElementById('editDokumentasiModal');
                const modalTitle = modal.querySelector('.modal-title');
                const nameInput = modal.querySelector('#name');
                const formEdit = modal.querySelector('form');

                modalTitle.textContent = 'Edit ' + name;
                nameInput.value = name;

                let formAction;
                if (role == 'admin') {
                    formAction = "{{ route('admin.pengelolaan.dokumentasi.edit') }}?slug=" + slug;
                    formEdit.setAttribute('action', formAction);
                } else if (role == 'pengurus') {
                    formAction = "{{ route('pengurus.pengelolaan.dokumentasi.edit') }}?slug=" + slug;
                    formEdit.setAttribute('action', formAction);
                }
            };

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
                        swal("Success, dokumentasi berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan dokumentasi dibatalkan!");
                    }
                });
            };

            table.on('draw', () => {
                const editButtons = document.querySelectorAll(
                    'button[data-target="#editDokumentasiModal"]');
                editButtons.forEach((button) => {
                    button.removeEventListener('click', handleEditDokumentasi);
                    button.addEventListener('click', handleEditDokumentasi);
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
