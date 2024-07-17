@extends('layouts.app')

@section('title', 'Berkas')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Berkas | {{ $berkass->name }}</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card p-3">
                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus')
                            <button class="btn btn-success float-right mb-3" data-target="#addBerkasAttachmentModal"
                                data-toggle="modal">Tambah</button>
                        @endif
                        @if ($attachments->isEmpty())
                            <a type="button" class="card border mt-3 card-default mb-3" style="text-decoration: none">
                                <div class="card-body">
                                    <h5 class="mb-1 text-center">Lampiran tidak di temukan</h5>
                                </div>
                            </a>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th class="text-center">Berkas</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attachments as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-capitalize">{{ $item->name }}</td>
                                            <td class="text-capitalize">{{ $item->type }}</td>
                                            @if ($item->type == 'link')
                                                <td><a href="{{ $item->data_path }}" target="_blank"
                                                        class="btn btn-primary w-100"><i class="far fa-eye"></i></a></td>
                                            @elseif ($item->type == 'file')
                                                <td><a href="{{ asset('storage/' . $item->data_path) }}" target="_blank"
                                                        class="btn btn-primary w-100"><i class="far fa-eye"></i></a></td>
                                            @endif
                                            <td>
                                                <a href="{{ route('admin.pemberkasana.attachments.delete', ['attachment_id' => $item->id]) }}" class="btn btn-danger" id="btn-delete"><i class="far fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addBerkasAttachmentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lampiran Berkas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if (auth()->user()->role == 'admin')
                    <form action="{{ route('admin.pemberkasan.attachment.add', ['slug' => $berkass->slug]) }}"
                        class="needs-validation" novalidate='' method="POST" enctype="multipart/form-data">
                    @elseif (auth()->user()->role == 'pengurus')
                        <form action="{{ route('pengurus.pengelolaan.dokumentasi.add') }}" class="needs-validation"
                            novalidate='' method="POST" enctype="multipart/form-data">
                @endif
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Lampiran</label>
                        <input type="text" name="name" id="name" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="visibility">Type Lampiran</label>
                        <div class="form-check">
                            <input class="form-check-input" name="type" type="radio" name="flexRadioDefault"
                                id="radioFile" value="file">
                            <label class="form-check-label" for="radioFile">
                                File
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="type" type="radio" name="flexRadioDefault"
                                id="radioLink" value="link">
                            <label class="form-check-label" for="radioLink">
                                Link
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input hidden type="" name="attachment" class="form-control" id="formInput"
                            placeholder="Masukan Link">
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
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                        swal("Success, lampiran berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan lampiran dibatalkan!");
                    }
                });
            };

            const deleteButtons = document.querySelectorAll('a[id="btn-delete"]');
            deleteButtons.forEach((button) => {
                button.removeEventListener('click', handleDeleteButton);
                button.addEventListener('click', handleDeleteButton);
            });
        });
    </script>

    <script>
        const radioFile = document.getElementById('radioFile');
        const radioLink = document.getElementById('radioLink');
        let formInput = document.getElementById('formInput');

        radioFile.addEventListener('click', () => {
            formInput.removeAttribute('hidden');
            formInput.setAttribute('type', 'file');
        });

        radioLink.addEventListener('click', () => {
            formInput.removeAttribute('hidden');
            formInput.setAttribute('type', 'text');
        });
    </script>
@endpush
