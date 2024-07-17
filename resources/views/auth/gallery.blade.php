@extends('layouts.app')

@section('title', 'Gallery')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
    <style>
        .row-1 {
            display: flex;
            flex-wrap: wrap;
            width: 100%
        }

        .row-1 .column {
            flex: 11.1%;
            width: auto;
            overflow: hidden;
            padding: 0 4px;
            height: 100%
        }

        .row-1 .column img {
            width: 100%;
            height: 100%;
            margin: 4px
        }

        @media only screen and (max-width: 768px) {
            .row-1 .column {
                flex: 50%
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Gallery | {{ $dokumentasi->name }}</h1>
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
                            <button class="btn btn-success float-right mb-3" data-target="#uploadImageModal"
                                data-toggle="modal">Tambah</button>
                        @endif
                        @if ($attachments->isEmpty())
                            <a type="button" class="card border mt-3 card-default mb-3" style="text-decoration: none">
                                <div class="card-body">
                                    <h5 class="mb-1 text-center">Foto Belum Diupload</h5>
                                </div>
                            </a>
                        @else
                            <div class="row-1">
                                @php
                                    $imagesPerColumn = 4;
                                    $totalImages = $attachments->count();
                                    $totalColumns = ceil($totalImages / $imagesPerColumn);
                                @endphp
                                @for ($i = 0; $i < $totalColumns; $i++)
                                    <div class="column">
                                        @foreach ($attachments->slice($i * $imagesPerColumn, $imagesPerColumn) as $attachment)
                                            <img class="rounded-lg" loading='lazy' src="{{ asset('storage/' . $attachment->image_path) }}"
                                                alt="" data-target="#previewImageModal"
                                                data-id="{{ $attachment->id }}" data-toggle="modal"
                                                onclick="previewImage(this.src)" data-role='{{ auth()->user()->role }}'
                                                data-slug="{{ $dokumentasi->slug }}" style="cursor: pointer">
                                        @endforeach
                                    </div>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="uploadImageModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Gambar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if (auth()->user()->role == 'admin')
                    <form action='{{ route('admin.gallery.dokumentasi.upload', $dokumentasi->slug) }}'
                        enctype="multipart/form-data" class="needs-validation" novalidate='' method="POST">
                    @elseif (auth()->user()->role == 'pengurus')
                        <form action='{{ route('pengurus.gallery.dokumentasi.upload', $dokumentasi->slug) }}'
                            enctype="multipart/form-data" class="needs-validation" novalidate='' method="POST">
                @endif
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="attachments" class="form-label">Upload Photo</label>
                        <input class="form-control" type="file" name="attachments[]" required id="attachments" multiple>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="previewImageModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="" alt="" class="img-fluid rounded-lg">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus')
                        <a href="" class="btn btn-danger" id="btn-delete"><i class="fa-regular fa-trash"></i></a>
                    @endif
                    <a href="" class="btn btn-success" id="btn-download"><i
                            class="fa-regular fa-download"></i></a>
                </div>
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
        const previewImage = (src) => {
            const modal = document.getElementById('previewImageModal');
            const img = modal.querySelector('img');
            img.src = src;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const handleLinkChange = (e) => {
                e.preventDefault();
                const btnPreview = e.currentTarget;
                const id = btnPreview.getAttribute('data-id');
                const slug = btnPreview.getAttribute('data-slug');
                const role = btnPreview.getAttribute('data-role');

                const modal = document.getElementById('previewImageModal');

                const linkDownload = modal.querySelector('#btn-download');
                const urlDownload = `{{ route('download.dokumentasi', ':slug') }}?img-id=${id}`;
                linkDownload.href = urlDownload.replace(':slug', slug);

                if (role == 'admin') {
                    const linkDelete = modal.querySelector('#btn-delete');
                    const urlDelete = `{{ route('admin.gallery.dokumentasi.delete', ':slug') }}?img-id=${id}`;
                    linkDelete.href = urlDelete.replace(':slug', slug);
                } else if (role == 'pengurus') {
                    const linkDelete = modal.querySelector('#btn-delete');
                    const urlDelete =
                        `{{ route('pengurus.gallery.dokumentasi.delete', ':slug') }}?img-id=${id}`;
                    linkDelete.href = urlDelete.replace(':slug', slug);
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
                        swal("Success, gambar dokumentasi berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan gambar dokumentasi dibatalkan!");
                    }
                });
            };

            const downloadButtons = document.querySelectorAll('img[data-target="#previewImageModal"]');
            downloadButtons.forEach((button) => {
                button.removeEventListener('click', handleLinkChange);
                button.addEventListener('click', handleLinkChange);
            });

            const deleteButtons = document.querySelectorAll('a[id="btn-delete"]');
            deleteButtons.forEach((button) => {
                button.removeEventListener('click', handleDeleteButton);
                button.addEventListener('click', handleDeleteButton);
            });
        });
    </script>
@endpush
