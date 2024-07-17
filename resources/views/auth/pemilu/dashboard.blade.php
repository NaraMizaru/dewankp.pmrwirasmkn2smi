@extends('layouts.app')

@section('title', 'Dashboard Pemilu')

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
                <h1 class="text-primary">Dashboard Pemilu</h1>
            </div>
            <div class="row">
                @if (!$pemilu)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-5">
                                <h4 class="text-primary text-center">Tidak Ada Pemilu Yang Sedang Berlangsung</h4>
                                @if (auth()->user()->role == 'admin')
                                    <p class="text-center">Buat Pemilu <a href="{{ route('admin.dashboard.pemilu', ['type' => 'event']) }}">Disini</a></p>
                                @elseif (auth()->user()->role == 'pengurus')
                                <p class="text-center">Buat Pemilu <a href="{{ route('pengurus.dashboard.pemilu', ['type' => 'event']) }}">Disini</a></p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-block">
                                <h4>{{ $pemilu->name }}</h4>
                                <p>{{ $pemilu->description }}</p>
                            </div>
                            <div class="card-body">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-md-6 col-sm-12">
                                        <canvas id="statusVote" data-voted="{{ $voted }}" data-no-vote="{{ $notVoted }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
        const chart = document.getElementById('statusVote')
        const voted = chart.getAttribute('data-voted')
        const noVote = chart.getAttribute('data-no-vote')
        var ctx = document.getElementById("statusVote").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        voted,
                        noVote,
                    ],
                    backgroundColor: [
                        '#6777ef',
                        '#cdd3d8',
                    ],
                    label: 'Status'
                }],
                labels: [
                    'Voted',
                    'No Vote',
                ],
            },
            options: {
                responsive: true,
                legend: {
                    position: 'right',
                },
            }
        });
    </script>
@endpush
