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
                @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus' && auth()->user()->pengurus->type == 'inti 6')
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <button data-target="#addProkerModal" data-toggle='modal' class="btn btn-success mb-3 w-100"
                                    {{ auth()->user()->role != 'admin' ? 'disabled' : '' }}><i class="fa-regular fa-plus"></i></button>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <a href="{{ route('download.proker.pdf', ['type' => 'all']) }}" class="btn btn-danger mb-3 w-100"><i
                                        class="fa-regular fa-file-pdf"></i></a>
                            </div>
                        </div>
                    </div>
                @endif

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
                                                @if (auth()->user()->role == 'admin')
                                                    <th class="text-right">Action</th>
                                                @endif
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
                                                            @if (auth()->user()->role == 'admin')
                                                                <td class="text-right">
                                                                    @if ($proker->status == 'tidak selesai')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <form
                                                                                action="{{ route('admin.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <form
                                                                                action="{{ route('pengurus.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @endif
                                                                    @elseif ($proker->status == 'ongoing')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @endif
                                                                    @elseif ($proker->status == 'selesai')
                                                                        <form action="">
                                                                            <button
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-dark"
                                                                                style="cursor: not-allowed"
                                                                                disabled>Finished</button>
                                                                        </form>
                                                                    @endif
                                                                    @if (auth()->user()->role == 'admin')
                                                                        <a href="{{ route('admin.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @elseif (auth()->user()->role == 'pengurus')
                                                                        <a href="{{ route('pengurus.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            @endif
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
                                                            @if (auth()->user()->role == 'admin')
                                                                <td class="text-right">
                                                                    @if ($proker->status == 'tidak selesai')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <form
                                                                                action="{{ route('admin.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <form
                                                                                action="{{ route('pengurus.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @endif
                                                                    @elseif ($proker->status == 'ongoing')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @endif
                                                                    @elseif ($proker->status == 'selesai')
                                                                        <form action="">
                                                                            <button
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-dark"
                                                                                style="cursor: not-allowed"
                                                                                disabled>Finished</button>
                                                                        </form>
                                                                    @endif
                                                                    @if (auth()->user()->role == 'admin')
                                                                        <a href="{{ route('admin.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @elseif (auth()->user()->role == 'pengurus')
                                                                        <a href="{{ route('pengurus.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            @endif
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
                                                            @if (auth()->user()->role == 'admin')
                                                                <td class="text-right">
                                                                    @if ($proker->status == 'tidak selesai')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <form
                                                                                action="{{ route('admin.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <form
                                                                                action="{{ route('pengurus.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @endif
                                                                    @elseif ($proker->status == 'ongoing')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @endif
                                                                    @elseif ($proker->status == 'selesai')
                                                                        <form action="">
                                                                            <button
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-dark"
                                                                                style="cursor: not-allowed"
                                                                                disabled>Finished</button>
                                                                        </form>
                                                                    @endif
                                                                    @if (auth()->user()->role == 'admin')
                                                                        <a href="{{ route('admin.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @elseif (auth()->user()->role == 'pengurus')
                                                                        <a href="{{ route('pengurus.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            @endif
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
                                                            @if (auth()->user()->role == 'admin')
                                                                <td class="text-right">
                                                                    @if ($proker->status == 'tidak selesai')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <form
                                                                                action="{{ route('admin.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <form
                                                                                action="{{ route('pengurus.startProker', $proker->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn mb-2 mt-2 btn-sm w-100 btn-primary">Start</button>
                                                                            </form>
                                                                        @endif
                                                                    @elseif ($proker->status == 'ongoing')
                                                                        @if (auth()->user()->role == 'admin')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @elseif (auth()->user()->role == 'pengurus')
                                                                            <button data-target="#finishProkerModal"
                                                                                data-toggle="modal"
                                                                                data-name='{{ $proker->name }}'
                                                                                data-id='{{ $proker->id }}'
                                                                                data-unit-name='{{ $item->name }}'
                                                                                data-user-role={{ auth()->user()->role }}
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-warning">Finish</button>
                                                                        @endif
                                                                    @elseif ($proker->status == 'selesai')
                                                                        <form action="">
                                                                            <button
                                                                                class="btn mb-2 mt-2 btn-sm w-100 btn-dark"
                                                                                style="cursor: not-allowed"
                                                                                disabled>Finished</button>
                                                                        </form>
                                                                    @endif
                                                                    @if (auth()->user()->role == 'admin')
                                                                        <a href="{{ route('admin.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @elseif (auth()->user()->role == 'pengurus')
                                                                        <a href="{{ route('pengurus.deleteProker', $proker->id) }}"
                                                                            id="btn-delete"
                                                                            class="btn mb-2 mt-2 btn-sm d-block btn-danger">
                                                                            <i class="fa-regular fa-trash"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            @endif
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


    <div class="modal fade" tabindex="-1" role="dialog" id="addProkerModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Proker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.addProker') }}" class="needs-validation" novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="unit_id">Unit Proker</label>
                            <select name="unit_id" id="unit_id" class="form-control">
                                @foreach ($unit as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Proker</label>
                            <input type="text" name="name" id="name" class="form-control"
                                autocomplete="off">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="finishProkerModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Proker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action class="needs-validation" novalidate='' method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Proker</label>
                            <input type="text" name="name" id="name" class="form-control" autocomplete="off"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Selesai Proker</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" autocomplete="off"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="dokumentasi_id">Dokumentasi Proker</label>
                            <select name="dokumentasi_id" id="dokumentasi_id" class="form-control">
                                <option value="NULL">Pilih Dokumentasi</option>
                                @foreach ($dokumentasi as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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
        document.addEventListener('DOMContentLoaded', function() {
            const handleFinishButton = (e) => {
                const button = e.currentTarget
                const id = button.getAttribute('data-id');
                // console.log(id)
                const name = button.getAttribute('data-name');
                const unitName = button.getAttribute('data-unit-name');
                const userRole = button.getAttribute('data-user-role');
                // console.log(userRole)

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
