@extends('layouts.app')

@section('title', 'Berkas')

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
                <h1 class="text-primary">Berkas | {{ $berkass->name }}</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card p-3">
                        @if ($attachments->isEmpty())
                            <a type="button" class="card border mt-3 card-default mb-3" style="text-decoration: none">
                                <div class="card-body">
                                    <h5 class="mb-1 text-center">Lampiran tidak di temukan</h5>
                                </div>
                            </a>
                        @else
                            @foreach ($attachments as $item)
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="text-white">{{ $item->name }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ asset('storage/' . $item->data_path) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                    </div>
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
