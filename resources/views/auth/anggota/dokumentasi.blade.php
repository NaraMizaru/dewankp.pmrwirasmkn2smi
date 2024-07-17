@extends('layouts.app')

@section('title', 'Dokumentasi')

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
                        @if ($dokumentasi->isEmpty())
                            <a type="button" class="card border mt-3 card-default mb-3" style="text-decoration: none">
                                <div class="card-body">
                                    <h5 class="mb-1 text-center">Dokumentasi Tidak Tersedia</h5>
                                </div>
                            </a>
                        @else
                            @foreach ($dokumentasi as $item)
                                <a href="{{ route('anggota.gallery.dokumentasi', $item->slug) }}"
                                    class="card border mt-3 card-default mb-3 p-3" style="text-decoration: none">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="mb-1">{{ $item->name }}</h5>
                                                {{-- <p class="text-danger text-capitalize">Status :
                                                    {{ $item->status }}</p> --}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
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
@endpush
