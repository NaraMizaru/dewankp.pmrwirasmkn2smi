@extends('layouts.app')

@section('title', 'Program Kerja')

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
                <h1 class="text-primary">Program Kerja</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Program Kerja Terlaksana</h4>
                            </div>
                            <div class="card-body">
                                {{ $prokerTerlaksanaCount }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Program Kerja Ongoing</h4>
                            </div>
                            <div class="card-body">
                                {{ $prokerOngoingCount }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Program Kerja Tidak Terlaksana</h4>
                            </div>
                            <div class="card-body">
                                {{ $prokerTidakTerlaksanaCount }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($unit as $item)
                    <div class="col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $item->name }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Name</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($item->id == 1)
                                                @if ($prokerUnit1->isEmpty())
                                                    <tr>
                                                        <td colspan="4" class="text-center">Proker tidak tersedia</td>
                                                    </tr>
                                                @else
                                                    @foreach ($prokerUnit1 as $proker)
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $proker->name }}
                                                            </td>
                                                            <td class="text-center text-capitalize">
                                                                @if ($proker->status == 'selesai')
                                                                    <div class="badge badge-success">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'ongoing')
                                                                    <div class="badge badge-warning">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'tidak selesai')
                                                                    <div class="badge badge-danger">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endif
                                            @if ($item->id == 2)
                                                @if ($prokerUnit2->isEmpty())
                                                    <tr>
                                                        <td colspan="4" class="text-center">Proker tidak tersedia</td>
                                                    </tr>
                                                @else
                                                    @foreach ($prokerUnit2 as $proker)
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $proker->name }}
                                                            </td>
                                                            <td class="text-center text-capitalize">
                                                                @if ($proker->status == 'selesai')
                                                                    <div class="badge badge-success">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'ongoing')
                                                                    <div class="badge badge-warning">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'tidak selesai')
                                                                    <div class="badge badge-danger">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endif
                                            @if ($item->id == 3)
                                                @if ($prokerUnit3->isEmpty())
                                                    <tr>
                                                        <td colspan="4" class="text-center">Proker tidak tersedia</td>
                                                    </tr>
                                                @else
                                                    @foreach ($prokerUnit3 as $proker)
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $proker->name }}
                                                            </td>
                                                            <td class="text-center text-capitalize">
                                                                @if ($proker->status == 'selesai')
                                                                    <div class="badge badge-success">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'ongoing')
                                                                    <div class="badge badge-warning">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'tidak selesai')
                                                                    <div class="badge badge-danger">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endif
                                            @if ($item->id == 4)
                                                @if ($prokerUnit4->isEmpty())
                                                    <tr>
                                                        <td colspan="4" class="text-center">Proker tidak tersedia</td>
                                                    </tr>
                                                @else
                                                    @foreach ($prokerUnit4 as $proker)
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $proker->name }}
                                                            </td>
                                                            <td class="text-center text-capitalize">
                                                                @if ($proker->status == 'selesai')
                                                                    <div class="badge badge-success">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'ongoing')
                                                                    <div class="badge badge-warning">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @elseif ($proker->status == 'tidak selesai')
                                                                    <div class="badge badge-danger">
                                                                        {{ $proker->status }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
            const handleFinishButton = (e) => {
                const button = e.currentTarget
                const id = button.getAttribute('data-id');
                // console.log(id)
                const name = button.getAttribute('data-name');
                const unitName = button.getAttribute('data-unit-name');
                const userRole = button.getAttribute('data-user-role');
                console.log(userRole)

                const modal = document.getElementById('finishProkerModal')
                const modalTitle = modal.querySelector('.modal-title');
                const unitSelect = modal.querySelector('#unit_id')
                const nameInput = modal.querySelector('#name')
                const formFinish = modal.querySelector('form')

                modalTitle.textContent = 'Finish Proker ' + unitName;
                nameInput.value = name;

                let formAction;
                if (userRole == 'admin') {
                    formAction = `{{ route('admin.finishProker') }}?id=` + id
                } else if (userRole == 'pengurus') {
                    formAction = `{{ route('pengurus.finishProker') }}?id=` + id
                }
                formFinish.setAttribute('action', formAction);
            }

            const handleDeleteProker = (e) => {
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
                        swal("Success, proker berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan proker dibatalkan!");
                    }
                });
            }

            const finishButtons = document.querySelectorAll('button[data-target="#finishProkerModal"]')
            finishButtons.forEach((button) => {
                button.removeEventListener('click', handleFinishButton);
                button.addEventListener('click', handleFinishButton);
            });

            const deleteButtons = document.querySelectorAll('a[id="btn-delete"]');
            deleteButtons.forEach((button) => {
                button.removeEventListener('click', handleDeleteProker);
                button.addEventListener('click', handleDeleteProker);
            });
        });
    </script>
@endpush
