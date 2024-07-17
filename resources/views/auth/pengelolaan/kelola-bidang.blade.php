@extends('layouts.app')

@section('title', 'Pengelolaan Bidang')

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
                <h1 class="text-primary">Pengelolaan Bidang</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4>Kelola Bidang</h4>
                        </div>
                        <div class="card-body">
                            <button data-toggle="modal" data-target="#addBidangModal" class="float-right btn btn-success mb-2">
                                <i class="fa-regular fa-plus"></i>
                            </button>
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                No
                                            </th>
                                            <th>Nama</th>
                                            <th>Slug</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bidang as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    {{ $item->slug }}
                                                </td>
                                                @if (auth()->user()->role == 'admin')
                                                    <td class="text-right">
                                                        <button data-target="#editBidangModal" data-toggle="modal" data-name='{{ $item->name }}' data-slug='{{ $item->slug }}' class="btn btn-warning"><i
                                                                class="fa-regular fa-pen-to-square"></i></button>
                                                        <a href="{{ route('admin.pengelolaan.delete', $item->slug) }}?pengelolaan=bidang" id="btn-delete" class="btn btn-danger"
                                                            title="Hapus Anggota"><i class="fa-regular fa-trash"></i></a>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="addBidangModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bidang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pengelolaan.tambah', ['pengelolaan' => 'bidang']) }}" class="needs-validation"
                    novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Bidang</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="editBidangModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bidang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pengelolaan.tambah', ['pengelolaan' => 'bidang']) }}" class="needs-validation"
                    novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Bidang</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off">
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

            const handleEditBidang = (e) => {
                const button = e.currentTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const slug = button.getAttribute('data-slug');

                const modal = document.getElementById('editBidangModal');
                const modalTitle = modal.querySelector('.modal-title');
                const nameInput = modal.querySelector('#name');
                const formEdit = modal.querySelector('form');

                modalTitle.textContent = 'Edit Bidang ' + name;
                nameInput.value = name;

                const formAction =
                    "{{ route('admin.pengelolaan.edit', ['pengelolaan' => 'bidang']) }}&&slug=" + slug;
                formEdit.setAttribute('action', formAction);
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
                        swal("Success, bidang berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan bidang dibatalkan!");
                    }
                });
            };

            table.on('draw', () => {
                const editButtons = document.querySelectorAll('button[data-target="#editBidangModal"]');
                editButtons.forEach((button) => {
                    button.removeEventListener('click', handleEditBidang);
                    button.addEventListener('click', handleEditBidang);
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
