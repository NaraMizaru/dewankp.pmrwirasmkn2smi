@extends('layouts.app')

@section('title', 'Pemilihan')

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
                <h1 class="text-primary">Pemilihan Umum</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                </div>
                <div class="col-md-5 col-sm-12">
                    <div class="card" style="height: 100%">
                        <div class="card-header bg-primary">
                            <h4 class="text-white">{{ $pemilu->name }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $pemilu->description }}</p>
                        </div>
                        <div class="card-footer border-top">
                            @if (auth()->user()->role == 'admin')
                            <a href="{{ route('admin.dashboard.pemilu.pemilihan', [$pemilu->slug, 'type' => 'pemilihan']) }}"
                                class="btn btn-primary float-right">
                                <i class="fa-regular fa-door-open mr-2"></i>Masuk
                            </a>
                            @elseif (auth()->user()->role == 'pengurus')
                            <a href="{{ route('pengurus.dashboard.pemilu.pemilihan', [$pemilu->slug, 'type' => 'pemilihan']) }}"
                                class="btn btn-primary float-right">
                                <i class="fa-regular fa-door-open mr-2"></i>Masuk
                            </a>
                            @elseif (auth()->user()->role == 'anggota')
                            <a href="{{ route('anggota.dashboard.pemilu.pemilihan', [$pemilu->slug, 'type' => 'pemilihan']) }}"
                                class="btn btn-primary float-right">
                                <i class="fa-regular fa-door-open mr-2"></i>Masuk
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-7 col-sm-12">
                  <div class="card p-4">
                    
                  </div>
                </div> --}}
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
