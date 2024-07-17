@extends('layouts.app')

@section('title', 'Vote Logs')

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
                <h1 class="text-primary">Vote Logs | {{ $pemilu->name }}</h1>
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
                              <a href="{{ route('download.pemilu.vote-logs', $pemilu->slug) }}" class="btn btn-danger"><i class="fa-regular fa-file-pdf"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama Pemilih</th>
                                            <th>Kandidat Terpilih</th>
                                            <th>Waktu Voting</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($voting as $item)
                                            <tr>
                                                <td class="text-center text-capitalize">{{ $loop->iteration }}</td>
                                                <td>{{ $item->user->fullname }}</td>
                                                <td class="text-capitalize">{{ $item->kandidat->name }}</td>
                                                <td>{{ $item->created_at }}</td>
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
        });
    </script>
@endpush
