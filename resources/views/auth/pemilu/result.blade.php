@extends('layouts.app')

@section('title', 'Result Pemilu')

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
                <h1 class="text-primary">Hasil Pemilu | {{ $pemilu->name }}</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" id="collapseDetailVoting">
                            <div class="row">
                                <div class="col">
                                    <h4>Detail Voting | Total User : {{ $totalUser }}</h4>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseBody"
                                    aria-expanded="true" aria-controls="collapseBody" onclick="toggleIcon()">
                                    <i class="fa-regular fa-plus" id="icon-button"></i>
                                </button>
                            </div>
                        </div>
                        <div id="collapseBody" class="collapse" aria-labelledby="collapseDetailVoting"
                            data-parent="#collapseDetailVoting">
                            <div class="card-body">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-md-6 col-sm-12">
                                        <canvas id="statusVote" data-voted="{{ $voted }}"
                                            data-no-vote="{{ $notVoted }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h4>Statistik</h4>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('download.pemilu.statisik', $pemilu->slug) }}" class="btn btn-danger"><i class="fa-regular fa-file-pdf"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="text-center">Peroleh Suara {{ $pemilu->name }}</h6>
                            <canvas id="countVote"></canvas>
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
        function toggleIcon() {
            const iconButton = document.getElementById('icon-button');
            const collapseBody = document.getElementById('collapseBody');
            const isExpanded = collapseBody.classList.contains('show');

            if (isExpanded) {
                iconButton.classList.remove('fa-minus');
                iconButton.classList.add('fa-plus');
            } else {
                iconButton.classList.remove('fa-plus');
                iconButton.classList.add('fa-minus');
            }
        }
    </script>
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
    <script>
        var ctx = document.getElementById("countVote").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@foreach($kandidat as $item) '{{ $item->name }}', @endforeach],
                datasets: [{
                    label: 'Count Vote',
                    data: [@foreach($kandidat as $item) '{{ $item->voting()->count() }}', @endforeach],
                    borderWidth: 2,
                    backgroundColor: '#6777ef',
                    borderColor: '#6777ef',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 150
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
            }
        });
    </script>
@endpush
